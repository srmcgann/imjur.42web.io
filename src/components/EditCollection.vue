<template>
  <div class="editCollection" ref="editCollection" tabindex="2000">
    <button
      @click="close()"
      class="cancelButton"
      title="close this view [ESC]"
    >
      close/cancel
    </button>
    <div class="editCollectionInner">
      <br><br>
      EDIT COLLECTION
      <br><br>
      update details below, click save<br>
      <div class="newCollectionForm">
        <input
          class="collectionFormInput"
          ref="name"
          v-model="collection.name"
          placeholder="collection name"
          @keydown.enter.stop.prevent="save()"
        ><br>
        <input
          class="collectionFormInput"
          v-model="collection.description"
          placeholder="description/hashtags"
          @keydown.enter.stop.prevent="save()"
        ><br>
        <br>
        set visibility<br>
        <div
          class="visibilityButton"
          @click.prevent.stop="setProperty('private', collection.private?0:1)"
          :class="{'private': collection.private, 'notPrivate': !collection.private}"
          :title="`toggle visibility. (currently: ${collection.private?'NOT':''} featured in public galleries)`"
        ></div><br>
        <div v-if="collection.meta.slugs.length" v-for="slug in collection.meta.slugs">
        </div>
        <div v-else>
          nothing added
        </div>
        <button @click="save()">save</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EditCollection',
  props: [ 'state', 'collection'],
  data(){
    return {
    }
  },
  methods: {
    setProperty(property, value){
      this.collection.[property] = value
    },
    save(){
      if(!this.collection.name) {
        alert('you must provide a name')
        return
      }
      let obj = {
        name: this.collection.name,
        id: this.collection.id,
        description: this.collection.description,
        slugs: this.collection.slugs,
        private: this.collection.private,
      }
      this.state.updateCollection(obj)
      this.state.editCollection = []
    },
    close(){
      this.state.editCollection = []
    }
  },
  mounted(){
    this.$nextTick(()=>{
      this.$refs.name.focus()
    })
  }
}
</script>

<style scoped>
  .editCollection{
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    width: 100vw;
    height: 100vh;
    font-size: 14px;
  }
  .editCollectionInner{
    text-align: center;
    padding: 25px;
    width: 100%;
    height: 100%;
    font-size: 25px;
    box-sizing: border-box;
    word-break: break-all;
    color: #fff;
    text-shadow: 2px 2px 2px #000;
    background: #201d ;
    word-break: auto-phrase;
  }
</style>
