<template>
  <div id="modal-confirm" :style="{'background-color': backgroundColor }">
  
    <div class="title">
      <span>{{ title }}</span>
    </div>
    
    <div class="message" v-if="message">
      <span>{{ message }}</span>
    </div>
    
    <div class="actions">
      <span class="button" @click="accept()"><i class="fa fa-check"></i></span>
      <span class="button" @click="cancel"><i class="fa fa-arrow-right"></i></span>
    </div>
    
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      type: 'getModalType',
      title: 'getModalTitle',
      message: 'getModalMessage',
      accept: 'getModalAccept',
      reject: 'getModalReject',
      backgroundColor: 'getModalColor',
      shouldDisplayModal: 'shouldDisplayModal'
    })
  },
  methods: {
    ...mapActions({
      toggleModal: 'toggleModal',
      clearModal: 'clearModal'
    }),
    cancel() {
      if (this.reject) {
        this.reject();
      } else {
        this.toggleModal();
        this.clearModal();
      }
    }
  }
};
</script>
