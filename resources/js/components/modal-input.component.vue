<template>
  <div id="modal-input" :style="{'background-color': backgroundColor }">
  
    <div class="title">
      <span>{{ title }}</span>
    </div>
    
    <div class="message" v-if="message">
      <span>{{ message }}</span>
    </div>
    
    <div class="input" v-if="shouldDisplayModal">
      <input v-focus type="number" class="app-input" v-model="inputValue" :style="{'background-color': backgroundColor }">
    </div>
    
    <div class="actions">
      <span class="button" @click="accept(inputValue)"><i class="fa fa-plus"></i></span>
      <span class="button" @click="cancel"><i class="fa fa-close"></i></span>
    </div>
    
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
    return {
      inputValue: ''
    };
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
  },
  directives: {
    focus: {
      inserted: (el, binding, vnode) => {
        console.log('então');
        el.focus();

        setTimeout(() => {
          el.focus();
        }, 100);
      }
    }
  }
};
</script>
