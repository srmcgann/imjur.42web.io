<template>
  <div class="modal" ref="modal" tabindex="1000">
    <button
      v-if="!this.state.uploadInProgress"
      @click="close()"
      class="cancelButton"
      title="close this view [ESC]"
    >
      close/cancel
    </button>
    <div class="modalInner" v-html="content"></div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: [ 'state', 'content' ],
  methods: {
    close(){
      this.state.closeModal()
    }
  },
  mounted(){
    this.$refs.modal.focus()
  }
}
</script>

<style scoped>
  .modal{
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    width: 100vw;
    height: 100vh;
    font-size: 14px;
  }
  .modalInner{
    text-align: center;
    padding: 25px;
    width: 100%;
    height: 100%;
    font-size: 25px;
    box-sizing: border-box;
    word-break: break-all;
    color: #fff;
    text-shadow: 2px 2px 2px #000;
    background: #001b;
    word-break: auto-phrase;
  }
</style>
