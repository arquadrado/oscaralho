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
      setModalInputType: 'setModalInputType',
      setModalTitle: 'setModalTitle',
      setModalMessage: 'setModalMessage',
      setModalOnInit: 'setModalOnInit',
      setModalAccept: 'setModalAccept',
      setModalReject: 'setModalReject',
      setModalColor: 'setModalColor',
      clearModal: 'clearModal'
    })
  }
}