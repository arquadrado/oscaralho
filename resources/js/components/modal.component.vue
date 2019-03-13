<template>
  <div id="modal" :class="{'show': shouldDisplayModal}">
    <div class="overlay" @click="toggleModal"></div>
    <div class="content">
      <component :is="modalType" v-if="shouldDisplayModal"></component>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import ConfirmModal from './modal-confirm.component.vue';
import InputModal from './modal-input.component.vue';
export default {
  components: {
    'confirm-modal': ConfirmModal,
    'input-modal': InputModal
  },
  computed: {
    ...mapGetters({
      shouldDisplayModal: 'shouldDisplayModal',
      modalType: 'getModalType'
    })
  },
  methods: {
    ...mapActions({
      toggleModal: 'toggleModal'
    })
  }
};
</script>
