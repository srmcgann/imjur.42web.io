<template>
  <div class="collectionTemplate" ref="collectionTempate" tabindex="2000">
    <button
      @click="close()"
      class="cancelButton"
      title="close this view [ESC]"
    >
      close/cancel
    </button>
    <div class="collectionTemplateInner">
      <br><br>
      CREATE A NEW COLLECTION
      <br><br>
      enter details below, click create,<br>
      then browse your assets and add<br>
      them to this collection if desired
      <div class="newCollectionForm">
        <input class="collectionFormInput" ref="name" v-model="name" placeholder="collection name"><br>
        <input class="collectionFormInput" v-model="description" placeholder="description"><br>
      </div><br>
      <div
        class="visibilityButton"
        @click.prevent.stop="setProperty('private', private?0:1)"
        :class="{'private': private, 'notPrivate': !private}"
        :title="`toggle visibility. (currently: ${private?'NOT':''} featured in public galleries)`"
      ></div><br>
      <button @click="submit()">create</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CollectionTemplate',
  props: [ 'state'],
  data(){
    return {
      name: '',
      description: '',
      slugs: [],
      originalSlugs: [],
      private: 0,
    }
  },
  methods: {
    setProperty(property, value){
      this[property] = value
    },
    submit(){
      let obj = {
        name: this.name,
        description: this.description,
        slugs: this.slugs,
        originalSlugs: this.originalSlugs,
        private: this.private,
      }
      this.state.createCollection(obj)
      this.state.showCollectionTemplate = false
    },
    close(){
      this.state.showCollectionTemplate = false
    }
  },
  mounted(){
    this.$refs.name.focus()
  }
}
</script>

<style scoped>
  .collectionTemplate{
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    width: 100vw;
    height: 100vh;
    font-size: 14px;
  }
  .collectionTemplateInner{
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
  .newCollectionForm{
    border-radius: 6px;
    margin-top: 50px;
    background: #40fa;
    color: #fff;
    font-size: 16px;
    text-align: center;
  }
  .collectionFormInput{
    font-family: Courier Prime;
    background: #000;
    border: 1px solid red;
    font-size: 14px;
    text-align: left;
    margin: 20px;
    width: calc(100% - 100px);
  }
</style>
