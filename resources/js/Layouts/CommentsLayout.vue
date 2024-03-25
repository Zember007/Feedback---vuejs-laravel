<template>
  <transition name="fade">
    <div class="modal" v-if="show">
      <div class="modal__backdrop" @click="closeModal()" />

      <div class="modal__dialog">
        <div class="modal__header">

          Комментарии: {{ comments.length }}

        </div>

        <div class="modal__body">
          <div v-for="comment in comments">
            <div class="modal__comment" v-if="comment['comment']">
              <div>{{ getDate(comment['updated_at']) }}</div>
              <div>{{ comment['comment'] }}</div>
              <div>ФИО: {{ comment['name'] }}</div>
              <div>Тел: {{ comment['phone'] }}</div>
            </div>
          </div>
        </div>

        <div class="modal__footer">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </transition>
</template>

<script>

export default {
  name: "CommentsLayout",
  props: {
    comments: {
      type: Object,
      required: true
    }
  },
  data() {

    return {
      show: false
    };
  },
  methods: {
    closeModal() {
      this.show = false;
      document.querySelector("body").classList.remove("overflow-hidden");
    },
    openModal() {
      this.show = true;
      document.querySelector("body").classList.add("overflow-hidden");
    },
    getDate(date) {
      var day = '' + new Date(date).getDay();
      var month = '' + new Date(date).getMonth();
      var data = ((day.length < 2) ? ('0' + day) : day) + '/' + ((month.length < 2) ? ('0' + month) : month) + '/' + new Date(date).getFullYear();
      return data;
    }
  },
  mounted() {

  },
};
</script>


<style lang="scss" scoped>
.modal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 30;
  overflow-x: hidden;
  overflow-y: auto;

  &__backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 1;
  }

  &__dialog {
    position: relative;
    max-width: 950px;
    background-color: #C4DDFF;
    border-radius: 5px;
    margin: 50px auto;
    display: flex;
    flex-direction: column;
    z-index: 2;
    padding: 20px;

    @media screen and (max-width: 992px) {
      width: 90%;
    }
  }

  &__close {
    width: 30px;
    height: 30px;
  }

  &__header {
    display: flex;
    align-items: flex-start;
    font-size: 27px;
    color: #156CB0;
    font-weight: 700;
    margin-bottom: 10px;
  }

  &__body {    
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: stretch;

   /*  &::-webkit-scrollbar {
      width: 12px;
    }

    &::-webkit-scrollbar-track {
      background: orange;
    }

    &::-webkit-scrollbar-thumb {
      background-color: blue;
      border-radius: 20px;
      border: 3px solid orange;
    } */
  }

  &__comment {
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 18px;
    color: #156CB0;
    background: #FFF;
    border-radius: 10px;
  }

  &__footer {
    padding: 10px 20px 20px;
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>