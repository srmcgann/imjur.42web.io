<template>
  <div class="collectionSelection">
    <button @click.stop.prevent="toggleShowCollection()" v-html="`${showCollection?'hide':'show'} collections`">
    </button><br>
    <div
      ref="collectionList"
      class="collectionList"
      :class="{'show': showCollection, 'hide': !showCollection}"
    >
      <label v-for="collection in state.collections" class="collectionLabel">
        <input type="checkbox" @change="updateSelection($event, collection)">
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
    }
  },
  methods: {
    toggleShowCollection(){
      this.showCollection = !this.showCollection
    },
    updateSelection(e, collection){
      console.log('e', e)
    }
  },
  mounted(){
    this.$refs.collectionList.style.height = Math.min(200, this.state.collections.length*21) + 'px'
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
    width: 200px;
    background: #123;
    display: none;
    line-height: 1em;
    overflow-y: auto;
    overflow-x: hidden;
  }
  .collectionLabel:hover{
    background: #0f44;
  }
  .collectionLabel{
    border: 1px solid #0f44;
    width: 100%;
  }
</style>
