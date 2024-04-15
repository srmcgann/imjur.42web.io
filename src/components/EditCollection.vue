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
      update details below<br>
      changes are effective instantly, universe-wide
      <div class="editCollectionForm">
        name<br>
        <input
          class="collectionFormInput"
          ref="name"
          v-model="collection.name"
          placeholder="collection name"
          @keyup.stop.prevent="save()"
        ><br><br>
        description<br>
        <input
          class="collectionFormInput"
          v-model="collection.meta.description"
          placeholder="description/hashtags"
          @keyup.stop.prevent="save()"
        ><br>
        <br>
        set visibility<br>
        <div
          class="visibilityButton"
          @click.prevent.stop="setProperty('private', collection.meta.private?0:1)"
          :class="{'private': collection.meta.private, 'notPrivate': !collection.meta.private}"
          :title="`toggle visibility. (currently: ${collection.meta.private?'NOT':''} featured in public galleries)`"
        ></div><br>
        <div v-if="collection.meta.slugs.length" v-for="slug in collection.meta.slugs">
        </div>
        <div v-else>
          <br><br>
          [nothing added yet - go browse!]
        </div>
        <!-- <button @click="save()">save</button> -->
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
      this.collection.meta[property] = value
      this.save()
    },
    save(){
      //if(!this.collection.name) {
      //  alert('you must provide a name')
      //  return
      //}
      let obj = {
        name: this.collection.name,
        id: this.collection.id,
        description: this.collection.meta.description,
        slugs: this.collection.meta.slugs,
        private: this.collection.meta.private,
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
  .editCollectionForm{
    border-radius: 6px;
    margin-top: 50px;
    background: #40f4;
    color: #fff;
    font-size: 16px;
    text-align: center;
    width: 500px;
    display: inline-block;
    padding: 20px;
  }
  .collectionFormInput{
    font-family: Courier Prime;
    color: #fff;
    background: #000;
    border: 5px solid #f004;
    font-size: 24px;
    text-align: center;
    margin: 5px;
    width: calc(100% - 60px);
  }
  .visibilityButton{
    width: 120px;
    height: 120px;
    background-size: 120px 120px;
  }
</style>
