import { presetUno, presetAttributify, presetIcons, defineConfig } from 'unocss';

export default defineConfig({
  theme: {
    colors: {
      primary: '#FF0000',
      secondary: '#FFBBB8',
      success: '#39FF14',
      danger: '#ff4141',
      info: '#4589D6',
      warning: '#F2B702',
      neutral: '#F7F8E8'
    }
  },
  shortcuts: [
    {
      'a-btn': 'rounded-1 px-4 py-2 bg-neutral hover:(shadow-md shadow-gray) transition',
    },
    [/^a-btn-(.*)-(.*)$/, ([, theme, shadow]) => {
      return `bg-${theme} hover:shadow-${shadow}`;
    }]
  ],
  presets: [
    presetUno(),
    presetAttributify(),
    presetIcons({
      collections: {
        ci: () => import('@iconify-json/ci/icons.json').then(i => i.default)
      }
    })
  ]
});