import { defineStore } from 'pinia';

export const useUser = defineStore('user', {
  state: (): User => (window as any).user,
});
