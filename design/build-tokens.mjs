#!/usr/bin/env node
/**
 * Asaka — génération des fichiers de design pour WordPress et Moodle.
 *
 * Source unique : design/tokens.json (même format que le design system Claude Design),
 * design/components.css, design/fonts/*, design/logos/*.
 *
 * Produit (fichiers commités, à ne pas éditer à la main) :
 *   wordpress/themes/asaka/theme.json                 (theme.base.json + presets)
 *   wordpress/themes/asaka/assets/css/tokens.css
 *   wordpress/themes/asaka/assets/css/components.css
 *   wordpress/themes/asaka/assets/fonts/*, assets/images/*
 *   moodle/theme/asaka/scss/_tokens.scss
 *   moodle/theme/asaka/scss/_components.scss
 *   moodle/theme/asaka/fonts/*, pix/logo*.svg
 *
 * Usage : node design/build-tokens.mjs [--check]
 *   --check : échoue si un fichier généré n'est pas à jour (CI).
 */
import { readFileSync, writeFileSync, copyFileSync, readdirSync, existsSync, mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const CHECK = process.argv.includes('--check');
const WP = join(ROOT, 'wordpress/themes/asaka');
const MD = join(ROOT, 'moodle/theme/asaka');
const HEADER = 'Généré par design/build-tokens.mjs depuis design/tokens.json — ne pas éditer.';

const tokens = JSON.parse(readFileSync(join(ROOT, 'design/tokens.json'), 'utf8'));
const stale = [];

function emit(path, content) {
  mkdirSync(dirname(path), { recursive: true });
  const current = existsSync(path) ? readFileSync(path) : null;
  const next = Buffer.isBuffer(content) ? content : Buffer.from(content);
  if (current && current.equals(next)) return;
  if (CHECK) { stale.push(path.replace(ROOT + '/', '')); return; }
  writeFileSync(path, next);
  console.log('  écrit', path.replace(ROOT + '/', ''));
}
const copy = (from, to) => emit(to, readFileSync(from));

// ---------- Lecture des tokens ----------
const firstTheme = tokens.color.themes[0].id;
const colorValue = (t) => (typeof t.value === 'string' ? t.value : t.value[firstTheme]);
const colors = tokens.color.tokens.map((t) => ({ name: t.name, value: colorValue(t) }));
const listOf = (family) => (tokens[family]?.tokens ?? []).map((t) => ({ name: t.name, value: t.value }));
const spacing = listOf('spacing');
const radius = listOf('radius');
const shadow = listOf('shadow');
const size = listOf('size');
const families = tokens.type.families;
const styles = tokens.type.groups.flatMap((g) => g.styles.map((s) => ({ ...s, family: s.family ?? g.family })));
const fonts = tokens.type.fonts;
const titleCase = (s) => s.replace(/(^|-)(\w)/g, (_, sep, c) => (sep ? ' ' : '') + c.toUpperCase());

// ---------- CSS custom properties (mêmes noms que tokens.css de Claude Design) ----------
const cssVars = [
  ...colors.map((c) => `  --${c.name}: ${c.value};`),
  ...[...spacing, ...radius, ...shadow, ...size].map((t) => `  --${t.name}: ${t.value};`),
  ...Object.entries(families).map(([k, v]) => `  --font-${k}: ${v};`),
].join('\n');
const tokensCss = `/* ${HEADER} */\n:root {\n${cssVars}\n}\n`;

// ---------- WordPress ----------
const base = JSON.parse(readFileSync(join(WP, 'theme.base.json'), 'utf8'));
const byName = Object.fromEntries(styles.map((s) => [s.name, s]));
const wpFontSizes = ['caption', 'body-sm', 'body', 'lead', 'title-3', 'title-2', 'title-1', 'display']
  .filter((n) => byName[n])
  .map((n) => ({ slug: n, name: titleCase(n), size: byName[n].fontSize, fluid: false }));
const fontFiles = fonts.map((f) => f.file.split('/').pop());
const theme = structuredClone(base);
theme.settings.color = {
  ...(theme.settings.color ?? {}),
  palette: colors.map((c) => ({ slug: c.name, name: titleCase(c.name), color: c.value })),
};
theme.settings.typography = {
  ...(theme.settings.typography ?? {}),
  fontFamilies: [{
    slug: 'sans',
    name: fonts[0].family,
    fontFamily: families.sans,
    fontFace: fonts.map((f) => ({
      fontFamily: f.family, fontWeight: String(f.weight), fontStyle: f.style ?? 'normal', fontDisplay: 'swap',
      src: [`file:./assets/fonts/${f.file.split('/').pop()}`],
    })),
  }],
  fontSizes: wpFontSizes,
};
theme.settings.spacing = {
  ...(theme.settings.spacing ?? {}),
  spacingSizes: spacing.map((s) => ({ slug: s.name, name: s.name, size: s.value })),
};
theme.settings.custom = {
  ...(theme.settings.custom ?? {}),
  radius: Object.fromEntries(radius.map((r) => [r.name.replace('radius-', ''), r.value])),
  shadow: Object.fromEntries(shadow.map((s) => [s.name.replace('shadow-', ''), s.value])),
  lineHeight: Object.fromEntries(styles.map((s) => [s.name, s.lineHeight])),
};
emit(join(WP, 'theme.json'), JSON.stringify(theme, null, 2) + '\n');
emit(join(WP, 'assets/css/tokens.css'), tokensCss);
emit(join(WP, 'assets/css/components.css'), `/* ${HEADER} (copie de design/components.css) */\n` + readFileSync(join(ROOT, 'design/components.css'), 'utf8'));
for (const f of fontFiles) copy(join(ROOT, 'design/fonts', f), join(WP, 'assets/fonts', f));
for (const f of readdirSync(join(ROOT, 'design/logos'))) copy(join(ROOT, 'design/logos', f), join(WP, 'assets/images', f));

// ---------- Moodle ----------
const scssVars = [
  ...colors.map((c) => `$asa-${c.name}: ${c.value};`),
  ...[...spacing, ...radius, ...size].map((t) => `$asa-${t.name}: ${t.value};`),
  ...shadow.map((t) => `$asa-${t.name}: ${t.value};`),
  `$asa-font-sans: ${families.sans};`,
].join('\n');
const fontFaces = fonts.map((f) => `@font-face {
  font-family: "${f.family}";
  src: url("[[font:theme|${f.file.split('/').pop()}]]") format("woff2");
  font-weight: ${f.weight};
  font-style: ${f.style ?? 'normal'};
  font-display: swap;
}`).join('\n');
emit(join(MD, 'scss/_tokens.scss'), `// ${HEADER}\n// Variables SCSS, utilisées par pre.scss avant Bootstrap.\n${scssVars}\n`);
emit(join(MD, 'scss/_components.scss'), `// ${HEADER}\n// Custom properties, polices et composants asa-* partagés avec WordPress.\n${tokensCss}\n${fontFaces}\n\n${readFileSync(join(ROOT, 'design/components.css'), 'utf8')}`);
for (const f of fontFiles) copy(join(ROOT, 'design/fonts', f), join(MD, 'fonts', f));
copy(join(ROOT, 'design/logos/asaka-logo.svg'), join(MD, 'pix/logo.svg'));
copy(join(ROOT, 'design/logos/asaka-logo-inverse.svg'), join(MD, 'pix/logo-inverse.svg'));

if (CHECK) {
  if (stale.length) {
    console.error('Fichiers générés périmés, lancer `make tokens` :\n  ' + stale.join('\n  '));
    process.exit(1);
  }
  console.log('Fichiers générés à jour.');
} else {
  console.log('Tokens générés.');
}
