<!--
todo

  * upload targets @ not-logged-in

  ✔ dropable files
  ✔ fix video & audio thumbnails
  ✔ file hash unifies same-upload sources
  ✔ fix loading animations
  ✔ preload loading (and other) assets
  ✔ display errors
  ✔ tile-able cards (flex)
  ✔ log original file name
  ✔ lightbox sim / previews
  ✔ checkboxes & "with selected" toobar
  ✔ uploading progress bars
  ✔ upload progress bars
  * download button on preview modal
  * users, optional logins/profiles
    └-> ✔ login button
        ✔ profile page
        * collections
        * comments
        * votes
        
  * URL uploads
  * admin panel
  * optional autodelete
  * log & display asset origin
  * social media metadata
  * gallery / trending
  * load/resource balancing
  * youtube field  // likely not to work without shell access
  
-->

<template>
  <div class="link" ref="anchor">
    <label v-if="state.loggedIn" :for="link.linkType+link.ct" class="checkboxLabel" :key="link.linkType+link.ct+'key'">
      <input type="checkbox" v-model="link.selected" @input="updateLinkSelected()" :id="link.linkType+link.ct">
      <span class="checkmark" style="margin-left: -30px;"></span>
      <span style="font-size:.75em;margin-top:5px;display:block;color:#4f88;padding:0;margin-left:-34px;">select</span><br>
    </label>
    
    <div class="views" v-html="views">
    </div>
    
    <div class="linkThumb" ref="linkThumb" @click.prevent.stop="preview()" title="view this asset"></div>
    <!--#{{link.ct+1}}-->
    <div class="linkButtons">
      <div class="copyLinkButton" @click.prevent.stop="state.copyLink(link.href)" title="copy link to clipboard"></div><br>
      <a :href="link.href" class="openButton" @click.prevent.stop="stte.openLink(link)" title="open link in new tab"></a><br>
      <div class="downloadButton" @click.prevent.stop="state.downloadLink(link, state.fileName(link))" title="download asset"></div><br>
    </div>
    <br>
    <table class="assetData">
      <tr><td class="tdLeft">age</td><td class="tdRight" v-html="age"></td></tr>
      <tr><td class="tdLeft">size</td><td class="tdRight" v-html="size"></td></tr>
      <tr><td class="tdLeft">name</td><td class="tdRight" v-html="state.fileName(link)"></td></tr>
    </table>
    
    <!-- <span style="visibility: hidden; position: absolute;" v-html="link.href" ref="href"></span> -->
    <!-- <span class="href" style="font-size: 1em" v-html="link.type"></span><br> -->
    <!-- <span class="href" style="font-size: 1em" v-html="'size: ' + link.size.toLocaleString('en-us')"></span><br> -->
  </div>
</template>

<script>

export default {
  name: 'Link',
  props: [ 'state', 'link' ],
  data(){
    return {
      c: document.createElement('canvas'),
      x: null,
      linkType: '',
      img: null,
      w: 0,
      h: 0,
      t: 0,
    }
  },
  computed: {
    views(){
      return 'views: ' + this.link.views.toLocaleString()
    },
    size(){
      let MB_ = 1024**2
      let tbytes = this.link.size
      let MB = tbytes / MB_ | 0
      let KB = ((tbytes / MB_) - MB) * MB_ / 1024 | 0
      let B = (((tbytes / MB_) - MB) * MB_ / 1024 - KB) * KB | 0
      let ret
      if(MB){
        ret = (Math.round(tbytes / MB_*100)/100) + ' MB'
      } else if(KB) {
        ret = (Math.round(((tbytes / MB_) - MB) * MB_ / 1024*100)/100) + ' KB'
      } else {
        ret = this.link.size.toLocaleString() + ' B'
      }
      return ret
    },
    age(){
      let tseconds = (((new Date()) - (new Date(this.link.date)))/1000|0) + 3600 * (((new Date).getTimezoneOffset()/60) - 4)
      let years = (tseconds/31536000)|0
      let days = (((tseconds/31536000)-years) * 31536000) / 86400 | 0
      let hours = (((((tseconds/31536000)-years) * 31536000) / 86400) - days) * 86400 / 3600 | 0
      let minutes = (((((((tseconds/31536000)-years) * 31536000) / 86400) - days) * 86400 / 3600) - hours) * 3600 / 60 | 0
      let seconds = (((((((((tseconds/31536000)-years) * 31536000) / 86400) - days) * 86400 / 3600) - hours) * 3600 / 60) - minutes) * 60| 0
      let ret = ''
      ret += years ? `${years} year${years>1?'s':''}, ` : ''
      ret += days ? `${days} day${days>1?'s':''}, ` : ''
      ret += hours ? `${hours} hour${hours>1?'s':''}, ` : ''
      ret += minutes ? `${minutes} minute${minutes>1?'s':''}` : ''
      //ret += seconds? `${seconds} second${seconds>1?'s':''}` : ''
      return ret ? ret : 'added just now...'
    }
  },
  methods: {
    updateLinkSelected(){
      if(this.link.selected){
        this.link.selected = false
      }else{
        this.link.selected = true
      }
      console.log('selected', this.link.selected)
    },
    preview(){
      this.state.previewPosition = this.link.ct
      this.state.previewPosition += this.link.linkType == 'userLink' ? this.state.links.length : 0
      this.state.previewLink = this.link
      this.state.showPreview = true
    },
    getThumb(){
      let l
      try{
        this.img.src = this.linkType == 'audio' ? this.state.URLbase + '/' + 'musicNotes.svg' : this.state.URLbase + '/' + 'thumb.php?res=resources/' + (this.link.originalSlug + '.' + this.link.href.split('.')[1])
      } catch(error){
        console.log(error)
      }
    },
    Draw(){
      this.x.globalAlpha = 1
      this.x.fillStyle='#0008'
      this.x.fillRect(0,0,this.c.width,this.c.height)
      let fillStyle = 'contain'
      let scl
      switch(fillStyle){
        case 'contain':
          scl = this.c.width/this.c.height <= 1.777777778 ? this.c.width/this.w : this.c.height/this.h
          break
        case 'cover':
          scl = this.c.width/this.c.height > 1.777777778 ? this.c.width/this.w : this.c.height/this.h
          break
      }
      let margin = 5
      let w = this.w * scl - margin
      let h = this.h * scl - margin
      this.x.drawImage(this.img,this.c.width/2-w/2,this.c.height/2-h/2,w,h)
      this.t += 1/60
      switch(this.linkType){
        case 'image':
          setTimeout(()=>{
            if(!this.img.width || !this.img.height) this.getThumb()
            requestAnimationFrame(this.Draw)
          }, 1000)
          break
        case 'video':
          if(!this.img.videoWidth || !this.img.videoHeight){
            setTimeout(()=>{
              this.getThumb()
              requestAnimationFrame(this.Draw)
            }, 1000)
          }else{
            requestAnimationFrame(this.Draw)
          }
          break
      }
    }
  },
  mounted(){
    this.$refs.linkThumb.appendChild(this.c)
    this.x = this.c.getContext('2d')
    this.linkType = this.link.type.split('/')[0]
    this.c.width = 500
    this.c.height = 500/1.77777778
    this.c.style.width = '200px'
    this.c.style.height = '113px'
    this.c.style.borderRadius = '20px'
    if(this.linkType == 'video'){
      this.img = document.createElement('video')
      this.img.loop = true
      this.img.muted = true
      this.img.oncanplay = () => {
        this.w = this.img.videoWidth
        this.h = this.img.videoHeight
        this.img.play()
        this.Draw()
      }
      this.img.src = this.link.href
    }
    if(this.linkType == 'image' || this.linkType == 'audio'){
      this.img = new Image()
      this.img.onload = () => {
        this.w = this.img.width
        this.h = this.img.height
        this.Draw()
      }
      this.getThumb()
    }
  }
}
</script>

<style scoped>
  .link:hover{
    background-color: #408a;
  }
  .link{
    display: inline-block;
    color: #acd;
    background-color: #002a;
    font-size: 20px;
    min-width: 268px;
    box-sizing: border-box;
    text-align: left;
    margin: 5px;
    word-break: break-word;
    border-radius: 10px;
    vertical-align: top;
    border: 3px solid #4086;
    padding: 5px;
  }
  .href{
    font-size: .6em;
    color: #0ff;
    margin: 5px;
    padding: 3px;
    width: calc(100% - 10px);
  }
  .linkThumb{
    float: left;
    width: 200px;
    height: 113px;
    margin: 5px;
    margin-top: 10px;
    cursor: pointer;
    background-size: contain;
    background-position: 20px 20px;
    background-repeat: no-repeat;
    background-color: #000;
    border-radius: 20px;
  }
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
  }
  .tdRight{
    text-align: left;
    color: #0f8;
    border-bottom: 1px solid #4fc2;
    padding: 3px;
  }
  .views{
    position: relative;
    margin-left: 200px;
  }
</style>

