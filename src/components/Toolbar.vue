<template>
  <div class="toolbar">
    <User :state="state" />
    <button @click="state.loadFiles()" class="toolbarButtons">
      upload
    </button>
    
    
    <div class="menu">
      <div class="parent" style="z-index: 2000">
        import tools &darr;
        <div class="sub" style="z-index: 1600; width: 420px; height: 80px;" code="">
          <div data-v-3b963fb4="" class="toolbarSection" style="border-top: 4px solid rgba(68, 0, 255, 0.667); vertical-align: middle; height: 63px; margin-top: 0px; width: 400px;"><input data-v-3b963fb4="" type="text" autofocus="" class="URLinput" placeholder="OR, upload from a URL... it might work!"><button data-v-3b963fb4="" class="goButton" title="download asset by URL [enter]">go</button></div>
        </div>        
      </div>
      <div class="parent" style="z-index: 1900">
        asset tools &darr;
        <div class="sub" code="console.log(Math.PI)" STYLE="width: 300px; height: 90px;">
          <div data-v-3b963fb4="" class="toolbarSection"><button data-v-3b963fb4="" class="toolbarButtons buttons" title="select all [ctrl + a]"> select all </button><button data-v-3b963fb4="" class="toolbarButtons disabledButton" disabled="" title="de-select all [ctrl + shift + a]"> deselect all </button></div>
        </div>
        <div class="sub" style="z-index: 1700;width: 375px;height: 90px;" code="console.log('this menu item')">
          <div data-v-3b963fb4="" class="toolbarSection"><span data-v-3b963fb4="" style="margin-left: 5px; font-size: 0.8em;">[w/selected→]</span><button data-v-3b963fb4="" class="toolbarButtons visibilityButton disabledButton privateDisabled" disabled="" title="set visibility to HIDDEN (from public galleries), for all selected" style="min-width: 50px;"></button><button data-v-3b963fb4="" class="toolbarButtons visibilityButton disabledButton notPrivateDisabled" disabled="" title="set visibility to VISIBLE (from public galleries), for all selected" style="min-width: 50px;"></button><button data-v-3b963fb4="" class="toolbarButtons disabledButton" disabled="" title="delete selected [del]"> delete </button></div>

        </div>
        <div class="sub" code="open('https://www.google.com', '_blank')">open google</div>
      </div>
      <div class="parent" style="z-index: 1800">
        [unused]
        <div class="sub" code="">sub a</div>
        <div class="sub" code="">sub b</div>
        <div class="sub" code="">sub c</div>
        <div class="sub" code="">
          submenus [unused]
          <div class="sub2" code="open('https://gifs.twilightparadox.com/df0898b287292aab8cf29efb75cb1783.gif', '_blank')">easter egg 1!</div>
          <div class="sub2" code="open('https://whr.rf.gd/a/full/2whT', '_blank')">easter egg 2!</div>
          <div class="sub2" code="open('https://emphasis.bizuit.com/?c=the%20super%20secret','_blank')">easter egg 3!</div>
          <div class="sub2" code="open('https://srmcgann.github.io/spaceflex','_blank')">easter egg 4!</div>
        </div>
      </div>
    </div>

    <!-- 
    <div v-if="state.loggedIn" class="toolbarSection">
      <span style="margin-left: 5px; font-size:.8em;">[w/selected&rarr;]</span>
      <button
        class="toolbarButtons visibilityButton"
        style="min-width: 50px;"
        :disabled="!someSelected"
        @click.prevent.stop="state.setLinkPropertySelected('private', 1)"
        :class="{'actionButton' : someSelected,
                 'disabledButton' : !someSelected,
                 'private': someSelected,
                 'privateDisabled': !someSelected}"
        :title="`set visibility to HIDDEN (from public galleries), for all selected`"
      ></button>
      <button
        class="toolbarButtons visibilityButton"
        style="min-width: 50px;"
        :disabled="!someSelected"
        @click.prevent.stop="state.setLinkPropertySelected('private', 0)"
        :class="{'actionButton' : someSelected,
                 'disabledButton' : !someSelected,
                 'notPrivate': someSelected,
                 'notPrivateDisabled': !someSelected}"
        :title="`set visibility to VISIBLE (from public galleries), for all selected`"
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
    <div class="toolbarSection" style="border-top: 4px solid #40fa;vertical-align: middle; height: 63px;margin-top: 0px; width: 313px">
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
    -->
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
    recurseMenus(el){
      if(el){
        if(el.nodeName == 'DIV'){
          let val=el.getAttribute('code')
          if(val) el.onclick = () => eval(val)
          el.childNodes.forEach(el2 => this.recurseMenus(el2))
        }
      }else{
        document.querySelectorAll('.menu').forEach( menu => this.recurseMenus(menu))
      }
    }
    keydown(e){
      if(e.keyCode == 46 || e.keyCode == 17 || this.state.keys[17] || e.keyCode == 18 || this.state.keys[18]) this.state.onkeydown(e)
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
    this.recurseMenus()
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
    height: 45px;
    padding: 2px;
    padding-bottom: 0;
    border-radius: 5px;
    vertical-align: middle;
    background-size: 45px;
  }
  .deleteButton{
    color: #f88;
    background-color: #400d;
    border-color: #8008;
  }
  .actionButton{
    color: #f88;
    background-color: #042d;
    border-color: #0808;
    border-radius: 40%;
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
    padding: 0;
    vertical-align: middle;
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
    border-radius: 10px;
  }
  .menu{
    padding: 10px;
    height: 500px;
    background: #40f2;
    position: absolute;
    top: 100px;
    left: 50%;
    transform: translate(-50%);
    text-align: center;
    font-size: 0;
    text-shadow: 2px 2px 4px #000;
  }
  div{
    box-sizing: border-box;
    border-radius: 0px;
    border: 2px solid #fff2;
    background: #000a;
    vertical-align: middle;
    padding-top: 9px;
    line-height: 1.125em;
  }
  .sub{
    margin-left: 33px;
    background: #084;
    text-align: left;
    padding-left: 10px;
  }
  .sub2 {
    margin-left: 66px;
    background: #208;
    text-align: left;
    padding-left: 10px;
  }
  .sub, .sub2{
    position: relative;
    display: none;
    z-index: 10;
    margin-top: 0px;
    min-height: 40px;
    min-width: 160px;
  }
  .parent{
    position: relative;
    cursor: pointer;
    width: 160px;
    padding-left: 10px;
    padding-right: 10px;
    text-align: center;
    height: 50px;
    font-size: 18px;
    margin: 0px;
    display: inline-block;
    z-index: 0;
  }
  .parent:hover .sub {
    display: block;
  }
  .sub:hover .sub2 {
    display: block;
  }
  .parent:hover{
    background: #084;
    color: #fff;
  }
  .sub:hover {
    background: #208;
    color: #fff;
    text-shadow: 2px 2px 2px #000;
  }
  .sub2:hover {
    background: #880;
    color: #fff;
    text-shadow: 2px 2px 2px #000;
  }

</style>
