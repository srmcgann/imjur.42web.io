<template>
  <div class="toolbar">
    <User :state="state" />
    <button @click="state.loadFiles()" class="toolbarButtons">
      upload
    </button>
    <div v-if="state.loggedIn" class="toolbarSection">
      <span style="margin-left: 5px; font-size:.8em;">[w/selected&rarr;]</span>
      <button
        class="toolbarButtons visibilityButton private"
        :disabled="!someSelected"
        @click.prevent.stop="state.setLinkPropertySelected('private', 1)"
        :class="{'deleteButton' : someSelected, 'disabledButton' : !someSelected}"
        :title="`set visibility to HIDDEN (from public galleries)`"
      ></button>
      <button
        class="visibilityButton notPrivate"
        :disabled="!someSelected"
        @click.prevent.stop="state.setLinkPropertySelected('private', 0)"
        :class="{'deleteButton' : someSelected, 'disabledButton' : !someSelected}"
        :title="`set visibility to VISIBLE (from public galleries)`"
      ></button>
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
    <div class="toolbarSection" style="border-top: 4px solid #40fa;vertical-align: middle; height: 44px;margin-top: -1px; width: 313px">
      <input
        type="text"
        autofocus
        ref="uploadURL"
        class="URLinput"
        @keydown.stop="keydown($event)"
        @keypress.enter="uploadByURL()"
        v-model="state.uploadFromURL"
        placeholder="OR, upload from a URL... it might work!"
      >
      <button @click="uploadByURL()" class="goButton" title="download asset by URL [enter]">go</button>
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
    keydown(e){
      if(e.keyCode == 18 || this.state.keys[18]) this.state.onkeydown(e)
    },
    uploadByURL(){
      let URL = this.state.uploadFromURL
      if(!URL) {
        this.$refs.uploadURL.focus()
        return
      }
      let assetFileName = this.state.uploadFromURL.split('/')
      assetFileName = decodeURIComponent(assetFileName[assetFileName.length-1].split('?')[0])
      this.state.modalContent = `<br><br><br><br><br><br>importing asset<br><br><div style="color: #f80">${assetFileName}<br><br></div>`
      this.state.showLoading=true
      this.state.showModal = true
      let batchMetaData = {
        loggedIn: this.state.loggedIn,
        userID: this.state.loggedinUserID,
        passhash: this.state.passhash,
        description: '',
      }
      if(URL){
        let sendData = {
          userName: this.state.loggedinUserName,
          passhash: this.state.passhash,
          URL,
          batchMetaData
        }
        fetch(`${this.state.URLbase}/` + 'uploadFromURL.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        }).then(res => res.json()).then(data => {
          if(data[0]){
            if(data[0]){
              this.state.uploadFromURL = ''
              data[1].map((v, j)=>{
                this.state.addLink(data[2][j], data[3][j], j, v, false, this.state.loggedinUserID, data[6][j], data[7][j], data[8][j], data[9], data[10][j], data[11][j], data[12][j],data[13][j], data[14][j])
                this.state.previewLink = this.state.links[this.state.links.length-1]
                this.state.showPreview = true
              })
            }
            this.state.modalContent = ''
            this.state.closeModal()
            if(this.state.loggedIn){
              this.state.links = []
              this.state.fetchUserLinks(this.state.loggedinUserID)
              this.state.jumpToPage(0)
              /*this.state.modalContent = '<div style="box-sizing: border-box;min-width:90vw; min-height: 50vh; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);background: #6666; color: #0f8; padding: 100px; text-align: left;">' + `imported asset:<br><br>${data[8][0]}` + '</div>'
              this.state.showModal = true*/
            }else{
              if(data[0]){
                this.state.modalContent = '<div style="box-sizing: border-box;min-width:90vw; min-height: 50vh; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);background: #0846; color: #8f8; padding: 100px; text-align: left;word-break: auto-phrase;">' + `excellent choice, uploading here...<br><br>you have not logged in though, which means your links will be lost soon.<br><br>If you register, nothing is needed except a name of your choosing and a password you will remember, then these uploads will be auto-saved to your new profile...<br><br>just don't close the page or refresh before you login or register!<br><br><br>thanks for using imjur!` + '</div>'
                this.state.showModal = true
                this.state.showRegister = true
                this.state.showLoginPrompt = true
              }else{
                this.state.modalContent = '<div style="min-width:90vw; min-height: 50vh; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);background: #8002; color: #f88; padding-top: 100px;">' + data[5] + '</div>'
              }
            }
          }else{
            this.state.modalContent = '<div style="box-sizing: border-box;min-width:90vw; min-height: 50vh; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);background: #6006; color: #f00; padding: 100px; text-align: left;">' + `failed to import asset:<br><br>error: &rarr;${data[5]}` + '</div>'
            this.state.showModal = true              
          }
          this.$refs.uploadURL = ''
          //this.$refs.uploadURL.focus()
          this.state.showLoading = false
        })
      }
    }
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
    width: calc(100vw);
    padding-right: 20px;
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
  .goButton{
    min-width: unset;
    width: 36px;
    height: 26px;
    margin-left: 6px;
    margin-top: 6px;
    position: absolute;
    padding: 0;
  }
  .URLinput{
    font-size: 16px;
    margin-top: 5px;
    margin-left: 3px;
    height: calc(100% - 9px);
    font-size: 14px;
    background: #021;
    border: 1px solid #0f8a;
    color: #4f8;
    width: 255px;
    display: inline-block;
  }
</style>
