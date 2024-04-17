<template>
  <div class="collectionSelection">
    <button
      @mousedown.stop.prevent
      @click.stop.prevent="toggleShowCollection()"
      class="collectionsButton"
      v-html="state.collections ? (showCollection?'hide':'show') : 'create'">
    </button><br>
    <div
      @mousedown.stop.prevent
      ref="collectionList"
      class="collectionList"
      :style="`height: ${colHeight}`"
      :class="{'show': showCollection, 'hide': !showCollection}"
    >
      <label
        v-for="collection in state.collections"
        class="collectionLabel"
        @mousedown.stop.prevent
      >
        <input
          :checked="checked(collection)"
          type="checkbox"
          @mousedown.stop.prevent
          @change="updateSelection($event, collection)"
        >
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
    checked(collection){
      return !!collection.meta.slugs.filter(v=>v==this.link.slug).length
    },
    toggleShowCollection(){
      if(!this.state.collections.length){
        this.state.showCollectionTemplate = true
      }else{
        this.state.click = true
        this.$nextTick(()=>{
          this.showCollection = !this.showCollection
        })
      }
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
  },
  watch:{
    'state.click'(val){
      if(val) this.showCollection = false
    }
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
    border: 4px solid #ff08;
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
