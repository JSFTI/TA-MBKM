import { defineStore } from 'pinia';

export const useUser = defineStore('user', {
  state: (): UserSession => (window as any).user,
});
