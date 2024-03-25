import { ref } from 'vue'

const isCreateSurvey = ref(false);


export function useCreateSurvey() {
  return {
    isCreateSurvey,
  }
}