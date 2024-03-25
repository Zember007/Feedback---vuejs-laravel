import { ref } from 'vue'

const isAddQuestions = ref(false);

export function useAddQuestions() {
  return {
    isAddQuestions,
  }
}