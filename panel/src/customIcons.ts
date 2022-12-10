// Integrating unocss preset icons to vuetify
// Utilities
import { h } from 'vue';
import type { IconProps } from 'vuetify/lib/framework.mjs';

const aliases = {
  collapse: 'i-mdi:chevron-up',
  complete: 'i-mdi:check',
  cancel: 'i-mdi:close-circle',
  close: 'i-mdi:close',
  delete: 'i-mdi:close-circle',
  // delete (e.g. v-chip close)
  clear: 'i-mdi:close-circle',
  success: 'i-mdi:check-circle',
  info: 'i-mdi:information',
  warning: 'i-mdi:alert-circle',
  error: 'i-mdi:close-circle',
  prev: 'i-mdi:chevron-left',
  next: 'i-mdi:chevron-right',
  checkboxOn: 'i-mdi:checkbox-marked',
  checkboxOff: 'i-mdi:checkbox-blank-outline',
  checkboxIndeterminate: 'i-mdi:minus-box',
  delimiter: 'i-mdi:circle',
  // for carousel
  sort: 'i-mdi:arrow-up',
  expand: 'i-mdi:chevron-down',
  menu: 'i-mdi:menu',
  subgroup: 'i-mdi:menu-down',
  dropdown: 'i-mdi:menu-down',
  radioOn: 'i-mdi:radiobox-marked',
  radioOff: 'i-mdi:radiobox-blank',
  edit: 'i-mdi:pencil',
  ratingEmpty: 'i-mdi:star-outline',
  ratingFull: 'i-mdi:star',
  ratingHalf: 'i-mdi:star-half-full',
  loading: 'i-mdi:cached',
  first: 'i-mdi:page-first',
  last: 'i-mdi:page-last',
  unfold: 'i-mdi:unfold-more-horizontal',
  file: 'i-mdi:paperclip',
  plus: 'i-mdi:plus',
  minus: 'i-mdi:minus',
};
const mdi = {
  // Not using mergeProps here, functional components merge props by default (?)
  component: (props: IconProps) => h(props.tag, {
    class: props.icon,
    disabled: props.disabled,
  }),
};
export { aliases, mdi };
