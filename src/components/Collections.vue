<template>
  <div class="collections" ref="collections" tabindex="1000">
    <button
      @click="close()"
      class="cancelButton"
      title="close this view [ESC]"
    >
      close/cancel
    </button>
    <div class="collectionsInner">
      &lt;&lt;&lt;  COLLECTIONS  >>>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Collections',
  props: [ 'state' ],
  methods: {
    close(){
      this.state.closePrompts()
    }
  },
  mounted(){
    this.$refs.collections.focus()
  }
}
</script>

<style scoped>
  .collections{
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    width: 100vw;
    height: 100vh;
    font-size: 14px;
  }
  .collectionsInner{
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
