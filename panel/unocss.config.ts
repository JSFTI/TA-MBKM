import {
  defineConfig,
  presetAttributify,
  presetIcons,
  presetUno,
  presetWebFonts,
  transformerDirectives,
  transformerVariantGroup,
} from 'unocss'

export default defineConfig({
  theme: {
    colors: {
      primary: '#FF0000',
      secondary: '#FFBBB8',
      success: '#1aad00',
      danger: '#ff4141',
      info: '#4589D6',
      warning: '#F2B702',
      neutral: '#F7F8E8',
    },
  },
  shortcuts: [
    {
      'text-themeable': 'text-dark dark:text-light',
    },
  ],
  presets: [
    presetUno(),
    presetAttributify(),
    presetIcons({
      scale: 1.2,
      warn: true,
      collections: {
        ic: () => import('@iconify-json/ic/icons.json').then(i => i.default as any),
        mdi: () => import('@iconify-json/mdi/icons.json').then(i => i.default as any),
      },
    }),
    presetWebFonts({
      fonts: {
        sans: 'DM Sans',
        serif: 'DM Serif Display',
        mono: 'DM Mono',
      },
    }),
  ],
  transformers: [
    transformerDirectives(),
    transformerVariantGroup(),
  ],
  safelist: [
    '!bg-primary', '!bg-secondary', '!bg-success', '!bg-danger',
    'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger',
  ],
})
