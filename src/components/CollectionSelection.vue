<template>
  <div class="collectionSelection">
    <button
      @click.stop.prevent="toggleShowCollection()"
      class="collectionsButton"
      v-html="`${showCollection?'hide':'show'}`">
    </button><br>
    <div
      ref="collectionList"
      class="collectionList"
      :style="`height: ${colHeight}`"
      :class="{'show': showCollection, 'hide': !showCollection}"
    >
      <label v-for="collection in state.collections" class="collectionLabel">
        <input type="checkbox" @change="updateSelection($event, collection)">
        {{collection.name}}
      </label>
    </div>
  </div>
</template>

<script>

export default {
  name: 'CollectionSelection',
  props: [ 'state', 'link' ],
  components: {
  },
  data(){
    return {
      showCollection: false,
    }
  },
  computed:{
    filteredCollections(){
      let ret = ['none']
      ret = [...ret, ...this.state.collections]
      return ret
    },
    colHeight(){
      return Math.min(300, this.state.collections.length*32) + 'px'
    }
  },
  methods: {
    toggleShowCollection(){
      this.showCollection = !this.showCollection
    },
    updateSelection(e, collection){
      let val = e.target.checked
      collection.meta.slugs = collection.meta.slugs.filter(slug=>{
        return slug !== this.link.slug
      })
      if(val){
        collection.meta.slugs.push(this.link.slug)
      }
      let obj = {
        name: collection.name,
        id: collection.id,
        description: collection.meta.description,
        slugs: collection.meta.slugs,
        private: collection.meta.private,
      }
      this.state.updateCollection(obj)
    }
  },
  mounted(){
  }
}
</script>

<style scoped>
  .collectionSelection{
  }
  .show{
    display: inline-block;
  }
  .hide{
    display: none;
  }
  .collectionList{
    width: 215px;
    background: #123;
    line-height: 1em;
    margin-top: 5px;
    overflow-y: auto;
    overflow-x: hidden;
  }
  .collectionLabel:hover{
    background: #0f44;
  }
  .collectionLabel{
    border: 1px solid #0f44;
    display: block;
  }
  .collectionsButton{
    line-height: 13px;
    font-size: 16px;
    height: 24px;
    margin-left: 53px;
  }
</style>
