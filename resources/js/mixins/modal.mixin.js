import { mapGetters, mapActions } from 'vuex';

export default {
  computed: {
    ...mapGetters({

    })
  },
  methods: {
    ...mapActions({
      toggleModal: 'toggleModal',
      setModalType: 'setModalType',
      setModalTitle: 'setModalTitle',
      setModalMessage: 'setModalMessage',
      setModalAccept: 'setModalAccept',
      setModalReject: 'setModalReject',
      setModalColor: 'setModalColor',
      clearModal: 'clearModal'
    })
  }
}