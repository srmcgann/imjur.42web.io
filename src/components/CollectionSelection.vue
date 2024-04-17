<template>
  <div class="collectionSelection">
    <button
      @mousedown.stop.prevent
      @click.stop.prevent="toggleShowCollection()"
      class="collectionsButton"
      style="background: #84fd"
      v-html="showCollection?'close':'assign'"
      v-if="!!state.collections.length"
    >
    </button>
    <button
      @mousedown.stop.prevent
      @click.stop.prevent="manageCollections()"
      class="collectionsButton"
      style="background: #4f8d"
      v-html="'manage'"
    ></button><br>
    <div
      @mousedown.stop.prevent
      ref="collectionList"
      class="collectionList"
      :style="`height: ${colHeight}`"
      :class="{'show': showCollection, 'hide': !showCollection}"
    >


      <div v-for="collection in state.collections" style="position: relative;">
        <label
          class="checkboxLabel collectionLabel"
          @mousedown.stop.prevent
        >
          <input
            :checked="checked(collection)"
            type="checkbox"
            @mousedown.stop.prevent
            @change="updateSelection($event, collection)"
          >
          <span class="checkmark" style="margin-left: -30px;"></span>
          <span class="collectionName">{{state.shortText(collection.name, 18)}}</span>
        </label><br>
      </div>


      <!-- <label
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
        {{state.shortText(collection.name, 18)}}
      </label>
      -->
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
      return Math.min(300, this.state.collections.length*28) + 'px'
    }
  },
  methods: {
    checked(collection){
      return !!collection.meta.slugs.filter(v=>v==this.link.slug).length
    },
    manageCollections(){
      this.state.showCollections = true
    },
    toggleShowCollection(){
      this.state.doMouseDown()
      if(!this.showCollection){
        this.$nextTick(()=>{
          this.$nextTick(()=>{
            this.showCollection = !this.showCollection
          })
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
    line-height: 28px;
    margin-top: 5px;
    padding-left: 2px;
    padding-top: 2px;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #0ff4;
  }
  .collectionLabel:hover{
    background: #0f44;
  }
  .collectionLabel{
    display: block;
  }
  .collectionName{
    font-size:14px;
    display:block;
    width: 161px;
    overflow: hidden;
    text-wrap: nowrap;
    color:#4f88;
    padding:0;
    margin-left:-34px;
  }
  .collectionsButton{
    line-height: 13px;
    font-size: 16px;
    height: 24px;
    margin: 0;
    margin-right: 10px;
    min-width: unset;
    width: 
  }
  input[type=checkbox]{
    margin: 4px;
    margin-right: 0;
  }
</style>
