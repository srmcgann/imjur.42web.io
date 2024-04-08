<template>
  <div class="preview" @mousemove="bumpNavButtonOpacity()" ref="preview">
    <button @click="close()" class="cancelButton" title="close this view">
      close/cancel
    </button>
    <div class="previewInner">
      <div class="slideshow" ref="slideshow"></div>
      <div v-if="state.links.length > 1" class="leftButton" @click="state.prev()" title="view previous asset [left arrow]"></div>
      <div v-if="state.links.length > 1" class="rightButton" @click="state.next()" title="view next asset [right arrow]"></div>
      <div v-if="state.userLinks.length > 1" class="leftButton" @click="state.prev()" title="view previous asset [left arrow]"></div>
      <div v-if="state.userLinks.length > 1" class="rightButton" @click="state.next()" title="view next asset [right arrow]"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Preview',
  props: [ 'state', 'link' ],
  data(){
    return {
      asset: null,
      linkType: ''
    }
  },
  methods: {
    bumpNavButtonOpacity(){
      this.$refs.preview.style.opacity = 1
    },
    close(){
      this.state.closePreview()
    }
  },
  mounted(){
    this.linkType = this.link.type.split('/')[0]

    if(this.linkType == 'image' || this.linkType == 'audio'){
      this.asset = document.createElement('div')
      this.asset.style.top = '50%'
      this.asset.style.left = '50%'
      this.asset.style.width = '100%'
      this.asset.style.height = '100%'
      this.asset.style.position = 'absolute'
      this.asset.style.transform = 'translate(-50%, -50%)'
      this.asset.style.objectFit = 'contain'
      this.asset.style.backgroundColor = '#000'
      this.asset.style.backgroundSize = 'contain'
      this.asset.style.backgroundRepeat = 'no-repeat'
      this.asset.style.backgroundPosition = 'center center'
      this.asset.style.backgroundImage = `url(${this.linkType == 'audio' ? 'musicNotes.svg' : this.state.URLbase + '/' + this.link.href})`
      this.$refs.slideshow.appendChild(this.asset)
    }
    if(this.linkType == 'audio'){
      this.asset = document.createElement('audio')
      this.asset.controls = true
      this.asset.style.position = 'absolute'
      this.asset.style.left = '50%'
      this.asset.style.top = '50%'
      this.asset.loop = true
      this.asset.style.transform = 'translate(-50%, -50%)'
      this.asset.oncanplay = () => {
        this.asset.play()
      }
      this.$refs.slideshow.appendChild(this.asset)
      this.asset.src = this.state.URLbase + '/' + this.link.href
    }
    if(this.linkType == 'video'){
      this.asset = document.createElement('video')
      this.asset.controls = true
      this.asset.style.position = 'absolute'
      this.asset.style.left = '50%'
      this.asset.style.top = '50%'
      this.asset.style.width = '100%'
      this.asset.style.height = '100%'
      this.asset.style.objectFit = 'contain'
      this.asset.style.background = '#000'
      this.asset.loop = true
      this.asset.style.transform = 'translate(-50%, -50%)'
      this.asset.oncanplay = () => {
        this.asset.play()
      }
      this.$refs.slideshow.appendChild(this.asset)
      this.asset.src = this.state.URLbase + '/' + this.link.href
    }
  },
  beforeUnmount(){
    this.asset.src = ''
  }
}
</script>

<style scoped>
  .preview{
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    width: 100vw;
    height: 100vh;
    font-size: 14px;
  }
  .cancelButton{
    background: #822;
    color: #f88;
    text-shadow: 1px 1px 3px #40f;
    font-weight: 900;
    width: 125px;
    font-family: Courier Prime;
    font-size: 14px;
    border: none;
    border-radius: 10px;
    padding: 5px;
    position: absolute;
    z-index: 1100;
    right: 20px;
    top: 14px;
  }
  .slideshow{
    margin: 100px;
    height: 100%;
    width: 100%;
    border: 1px solid red;
  }
  .leftButton, .rightButton{
    width: 100px;
    height: 100px;
    position: fixed;
    top: 50%;
    transform: translate(0, -50%);
    background-repeat: no-repeat;
    background-position: center center;
    background-size: contain;
    cursor: pointer;
  }
  @keyframes fadeOut{
    0% {
      opacity: 1;
    }
    75% {
      opacity: 1;
    }
    100% {
      opacity: 0;
      box-shadow: 0 0 150px 150px #ff4c0022;
    }
  }
  .leftButton{
    background-image: url(../assets/leftButton.png);
    left: 10px;
    opacity: 0;
  }
  .rightButton{
    background-image: url(../assets/rightButton.png);
    right: 10px;
    opacity: 0;
    animation: fadeOut 5s 1 linear;
  }
  .previewInner{
    text-align: center;
    padding: 25px;
    width: 100%;
    height: 100%;
    font-size: 25px;
    color: #fff;
    text-shadow: 2px 2px 2px #000;
    background: #001b;
  }
</style>