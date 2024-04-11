<template>
  <table class="assetData">
    <tr v-if="state.showPreview"><td class="tdLeft">views</td><td class="tdRight" v-html="state.views(link)"></td></tr>
    <tr>
      <td class="tdLeft">
        slug
      </td>
      <td class="tdRight">
        <a
          :href="state.URLbase + '/' + link.href"
          target="_blank"
          title="open link in new tab"
          v-html="link.slug"
          class="assetDataSlug"
        >
        </a>
      </td>
    </tr>
    <tr><td class="tdLeft">name</td><td class="tdRight" v-html="state.fileName(link)"></td></tr>
    <tr><td class="tdLeft">date uploaded</td><td class="tdRight" v-html="state.prettyDate(link)"></td></tr>
    <tr><td class="tdLeft">age</td><td class="tdRight" v-html="state.age(link)"></td></tr>
    <tr><td class="tdLeft">size</td><td class="tdRight" v-html="state.size(link)"></td></tr>
    <tr><td class="tdLeft">id</td><td class="tdRight" v-html="link.id"></td></tr>
    <tr><td class="tdLeft">origin</td><td class="tdRight" v-html="link.origin.split(':')[0]"></td></tr>
    <!-- <tr><td class="tdLeft">first seen</td><td class="tdRight"v-html="state.firstSeen(link)"></td></tr> --> 
  </table>
</template>

<script>
import Pages from './Pages'

export default {
  name: 'AssetData',
  props: [ 'state', 'link' ],
  components: {
  },
  data(){
    return {
    }
  },
  computed:{
  },
  methods: {
  },
  mounted(){
  }
}
</script>

<style scoped>
  .assetData{
    border-collapse: collapse;
    font-size: 14px;
    text-shadow: 2px 2px 2px #000;
    background: #0003;
    width: 100%;
  }
  .tdLeft{
    text-align: right;
    color: #f80;
    border-bottom: 1px solid #4fc2;
    padding: 3px;
    max-width: 65px;
  }
  .tdRight{
    text-align: left;
    color: #0f8;
    border-bottom: 1px solid #4fc2;
    padding: 3px;
  }
  .assetDataSlug{
    background-color: #08f;
    text-shadow: 1px 1px 2px #000;
    color: #fff;
    border-radius: 5px;
    min-width: 85px;
    display: inline-block;
    background-image: url(../assets/open.png);
    background-position: 85px 3px;
    background-repeat: no-repeat;
    background-size: 10px 10px;
    padding-left: 10px;
    padding-right: 10px;
  }
</style>
