<template>
  <div class="toolbar">
    <User :state="state" />
    <button @click="state.loadFiles()" class="toolbarButtons">
      upload
    </button>
    <div class="username" v-if="state.loggedIn">
      <span style="font-size: .75em;">welcome,&nbsp;&nbsp;&nbsp;</span><br>
      {{state.username}}
    </div>
    <div v-if="state.loggedIn" class="toolbarSection">
      <span style="margin-left: 5px; font-size:.8em;">[w/selected&rarr;]</span>
      <button
        @click="state.deleteSelected()"
        class="toolbarButtons"
        :disabled="!someSelected"
        title="delete selected [del]"
        :class="{'deleteButton' : someSelected, 'disabledButton' : !someSelected}"
      >
        delete
      </button>
    </div>
    <div v-if="state.loggedIn" class="toolbarSection">
      <button
        @click="state.selectAll()"
        class="toolbarButtons"
        :disabled="allSelected"
        title="select all [ctrl + a]"
        :class="{'buttons' : !allSelected, 'disabledButton' : allSelected}"
      >
        select all
      </button>
      <button
        @click="state.deSelectAll()"
        class="toolbarButtons"
        :disabled="!someSelected"
        title="de-select all [ctrl + shift + a]"
        :class="{'buttons' : someSelected, 'disabledButton' : !someSelected}"
      >
        deselect all
      </button>
    </div>
    <div class="toolbarSection" style="border-top: 4px solid #40fa;vertical-align: middle; height: 36px; margin-top: -2px;">
      <input
        type="text"
        style="width:360px;height: 100%;display: inline-block;font-size: 20px; background: #000; color: #4f8;"
        @keydown.prevent.stop
        v-model="state.uploadFromURL"
        placeholder="upload from a URL... it might work!" class="uploadFromURL"
      >
    </div>
  </div>
</template>

<script>
import User from './User'

export default {
  name: 'Toolbar',
  props: [ 'state' ],
  components: {
    User
  },
  data(){
    return {
    }
  },
  computed: {
    someSelected(){
      return this.state.links.filter(v=>v.selected).length || this.state.userLinks.filter(v=>v.selected).length
    },
    allSelected(){
      return (this.state.links.filter(v=>v.selected).length + this.state.userLinks.filter(v=>v.selected).length) == this.state.links.length + this.state.userLinks.length
    }
  },
  methods: {
  },
  mounted(){
  }
}
</script>

<style scoped>
  .toolbar{
    background: linear-gradient(90deg, #402, #111c, #3333);
    color: #4fa;
    font-size: 20px;
    position: absolute;
    top: 52px;
    z-index: 100;
    width: calc(100vw - 160px);
    padding-right: 135px;
  }
  .toolbarSection{
    display: inline-block;
    border: 4px solid #40fa;
  }
  .toolbarButtons{
    margin: 5px;
    min-width: 100px;
    padding: 2px;
    padding-bottom: 0;
    border-radius: 5px;
  }
  .username{
    display: inline-block;
    position: absolute;
    text-align: right;
    right: 5px;
    line-height: .8em;
  }
  .deleteButton{
    color: #f88;
    background-color: #400d;
    border-color: #8008;
  }
  .disabledButton{
    color: #888;
    background-color: #333d;
    border-color: #0008;
  }
  .uploadFromURL{
    font-size: 20px;
  }
</style>
