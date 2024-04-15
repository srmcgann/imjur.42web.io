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
      &lt;&lt;&lt;  COLLECTIONS  >>><br><br>
      <button
        v-if="state.collections.length"
        @click="state.showCollectionTemplate=true"
        title="create new collection"
        style="font-size: 32px;"
      >
      +
      </button>
      <table class="collectionsTable" v-if="state.collections.length">
        <tr>
          <th>name</th>
          <th>date</th>
          <th>items</th>
          <th>tools</th>
        </tr>
        <tr v-for="collection in state.collections">
          <td v-html="collection.name"></td>
          <td v-html="collection.meta.date"></td>
          <td v-html="collection.meta.slugs.length"></td>
          <td>
            <div class="linkButtons">
              <div
                class="visibilityButton"
                @click.prevent.stop="state.setCollectionProperty(collection, 'private', collection.meta.private?0:1)"
                :class="{'private': collection.meta.private, 'notPrivate': !collection.meta.private}"
                :title="`toggle visibility. (currently: ${collection.meta.private?'NOT':''} featured in public galleries)`"
              ></div>
              <div
                class="copyLinkButton"
                @click.prevent.stop="state.copyLink(collection.meta.href)"
                title="copy link to clipboard"
              ></div>
              <a
                :href="state.URLbase + '/' + collection.meta.href"
                class="openButton"
                @click.prevent.stop="state.openCollection(collection)"
                title="open link in new tab"
              ></a>
              <!-- <div
                class="downloadButton"
                @click.prevent.stop="state.downloadLink(link, state.fullFileName(link))"
                title="download asset"
              ></div> -->
              <div
                class="deleteSingleButton"
                @click.prevent.stop="state.deleteCollection(collection)"
                title="delete this collection"
              ></div>
            </div>
          </td>
        </tr>
      </table>
      <CollectionTemplate  v-else :state="state" />
    </div>
  </div>
</template>

<script>
import CollectionTemplate from './CollectionTemplate.vue'

export default {
  name: 'Collections',
  props: [ 'state' ],
  components:{
    CollectionTemplate,
  },
  methods: {
    close(){
      this.state.closePrompts()
    }
  },
  mounted(){
    this.$refs.collections.focus()
    this.state.fetchCollections(this.state.loggedinUserID)
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
    background: #102d ;
    word-break: auto-phrase;
  }
  tr{
    background: #123d;
  }
  th{
    padding-left: 10px;
    padding-right: 10px
    border-top: 4px solid #f008;
    border: 1px solid #f003;
    border-bottom: 4px solid #f008;
  }
  td{
    font-size: 14px;
    padding-left: 10px;
    padding-right: 10px;
    border: 1px solid #f003;
    border-top: 4px solid #f008;
    border-bottom: 4px solid #f008;
  }
  .collectionsTable{
    position: relative;
    left: 50%;
    transform: translate(-50%);
  }
  button{
    background: #0f8;
    padding: 0
  }
</style>
