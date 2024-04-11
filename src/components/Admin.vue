<template>
  <div
    class="admin"
    ref="admin"
    tabindex="1000"
    v-if="state.showAdmin"
  >
    <div class="modalInner">
      
    </div>
  </div>
  <button
    class="adminButton"
    @click="state.showAdmin=!state.showAdmin"
    v-html="state.showAdmin ? 'exit admin view' : 'show admin view'"
  >
  </button>
</template>

<script>

export default {
  name: 'Admin',
  props: [ 'state' ],
  components: {
  },
  data(){
    return {
    }
  },
  methods: {
  },
  mounted(){
  }
}
</script>

<style scoped>
.admin{
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
  width: 100vw;
  height: 100vh;
  font-size: 14px;
}
.adminButton{
  position: fixed;
  bottom: 25px;
  left: 50%;
  transform: translate(-50%);
  min-width: 150px;
  background: #f80;
  color: #000;
  border: 2px solid #f00;
}
.modalInner{
  background: #420c;
}
</style>
