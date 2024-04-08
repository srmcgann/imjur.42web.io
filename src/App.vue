<template>
  <div>
    <Header       :state="state" />
    <Toolbar      :state="state" />
    <Main         :state="state" />
    <Footer       :state="state" />
    <UserSettings :state="state" v-if="state.userSettingsVisible" />
    <LoginPrompt  :state="state" v-if="state.showLoginPrompt"/>
    <Modal
      :state="state"
      v-if="state.showModal"
      :content="state.modalContent"
    />
    <Preview
      :state="state"
      v-if="state.showPreview"
      :link="state.previewLink"
    />
    <div id="copyConfirmation"><div id="innerCopied">COPIED!</div></div>
  </div>
</template>

<script>
import Main from './components/Main'
import Modal from './components/Modal'
import Header from './components/Header'
import Footer from './components/Footer'
import Preview from './components/Preview'
import Toolbar from './components/Toolbar'
import LoginPrompt from './components/LoginPrompt'
import UserSettings from './components/UserSettings'

export default {
  name: 'App',
  components: {
    Header,
    Toolbar,
    Main,
    Footer,
    UserSettings,
    Modal,
    Preview,
    LoginPrompt,
  },
  data(){
    return {
      state: {
        footerMsg: '<b><span style="transform: scaleX(4.1);display: inline-block; margin-left: 86px; ">IMJUR</span></b><br>A FREE DIGITAL ASSET<br><span style="transform: scaleX(.87);display: inline-block; margin-left: -18px;">HOSTING SERVICE - ©'+(new Date()).getFullYear() + `</span><br><a href="mailto:whitehotrobot@gmail.com"><span style="transform: scaleX(.87);display: inline-block; margin-left: -18px;">whitehotrobot@gmail.com</span></a>`,
        links: [],
        userLinks: [],
        uploadInProgress: false,
        showModal: false,
        fetchUserLinks: null,
        setCookie: null,
        mode: null,
        deleteSelected: null,
        getAvatar: null,
        showPreview: false,
        rootDomain: location.hostname,
        modalContent: '',
        modalQueue: [],
        previewLink: null,
        passhash: '',
        loggedinUserID: null,
        closeModal: null,
        closePreview: null,
        closePrompts: null,
        defaultAvatar: 'avatarDefault.png',
        loggedInUser: {
          avatar: 'avatarDefault.png',
        },
        loggedinUserName: '',
        copy: null,
        next: null,
        prev: null,
        login: null,
        register: null,
        URLbase: null,
        logout: null,
        regusername: '',
        username: '',
        userView: false,
        password: '',
        maxResultsPerPage: 8,
        showUserSettings: null,
        invalidLoginAttempt: false,
        displayLoginRequired: false,
        userSettingsVisible: false,
        jumpToPage: null,
        regpassword: '',
        showUploadModal: false,
        loadingAssets: true,
        checkLogin: null,
        search: {
          string: '',
          //audiocloudTracks: [],
          hits: 0,
          inProgress: false,
          timer: 0,
          timerHandle: null,
          exact: false,
          allWords: true
        },
        confirmpassword: '',
        totalPages: 0,
        totalUserPages: 0,
        setLinksOwner: null,
        curPage: 0,
        curUserPage: 0,
        regressPage: null,
        advancePage: null,
        lastPage: null,
        firstPage: null,
        selectAll: null,
        deSelectAll: null,
        showLoginPrompt: false,
        loadFiles: null,
        showRegister: false,
        loggedIn: false,
        loginPromptVisible: false,
        getPages: null,
        previewPosition: 0,
        keys: Array(128).fill(false),
      }
    }
  },
  methods:{
    prev(){
      if(!this.state.showPreview) return
      this.state.showPreview = false
      this.state.previewPosition -= 1
      if(this.state.previewPosition<0) this.state.previewPosition = this.state.userLinks.length + this.state.links.length - 1
      if(this.state.previewPosition>this.state.links.length-1){
        this.state.previewLink = this.state.userLinks[this.state.previewPosition - this.state.links.length]
      }else{
        this.state.previewLink = this.state.links[this.state.previewPosition]
      }
      this.$nextTick(()=>{
        this.state.showPreview = true
      })
    },
    next(){
      if(!this.state.showPreview) return
      this.state.showPreview = false
      this.state.previewPosition++
      this.state.previewPosition %= this.state.userLinks.length + this.state.links.length
      if(this.state.previewPosition>this.state.links.length-1){
        this.state.previewLink = this.state.userLinks[this.state.previewPosition - this.state.links.length]
      }else{
        this.state.previewLink = this.state.links[this.state.previewPosition]
      }
      this.$nextTick(()=>{
        this.state.showPreview = true
      })
    },
    register(){
      console.log('registering')
      this.state.showLoginPrompt = true
      this.state.showRegister = true
    },
    showUserSettings(){
      document.getElementsByTagName('HTML')[0].style.overflowY = 'hidden'
      this.state.userSettingsVisible = true
    },
    firstPage(){
      let search = this.state.search.string ? ('/1/' + (this.state.search.string)) : ''
      switch(this.state.mode){
        case 'u':
          window.location.href = this.URLbase + '/u/' + this.state.user.name + search
        break
        case 'default':
          //window.location.href = this.URLbase + search
          this.state.curPage = 0
          this.state.fetchUserLinks(this.state.loggedinUserID)
          history.pushState(null,null,this.URLbase + '/' + (this.state.curPage + 1))
        break
        case 'track':
          window.location.href = this.URLbase + '/track/' + this.state.curTrack + search
        break
      }
    },
    jumpToPage(pageNo){
      let search = this.state.search.string ? ('/' + (this.state.search.string)) : ''
      switch(this.state.mode){
        case 'u':
        window.location.href = this.URLbase + '/u/' + this.user.name + '/' + pageNo + search
        break
        case 'default':
          //window.location.href = this.URLbase + '/' + pageNo + search
          this.state.curPage = pageNo
          if(this.state.loggedIn) this.state.fetchUserLinks(this.state.loggedinUserID)
          if(this.state.curPage){
            history.pushState(null,null,this.URLbase + '/' + (this.state.curPage + 1))
          }else{
            history.pushState(null,null,this.URLbase)
          }
        break
        case 'track':
        window.location.href = this.URLbase + '/track/' + this.decToAlpha(this.state.curTrack) + '/' + pageNo + search
        break
      }
    },
    lastPage(){
      let search = this.state.search.string ? ('/' + (this.state.search.string)) : ''
      switch(this.state.mode){
        case 'u':
          window.location.href = this.URLbase + '/u/' + this.state.user.name + '/' + this.state.totalUserPages + search
        break
        case 'default':
          //window.location.href = this.URLbase + '/' + this.state.totalPages + search
          this.state.curPage = this.state.totalPages - 1
          if(this.state.loggedIn) this.state.fetchUserLinks(this.state.loggedinUserID)
          history.pushState(null,null,this.URLbase + '/' + (this.state.curPage + 1))
        break
        case 'track':
          window.location.href = this.URLbase + '/track/' + this.decToAlpha(this.state.curTrack) + '/' + this.state.totalPages + search
        break
      }
    },
    advancePage(){
      let search = this.state.search.string ? ('/' + (this.state.search.string)) : ''
      switch(this.state.mode){
        case 'u':
          window.location.href = this.URLbase + '/u/' + this.state.user.name + '/' + (this.state.curUserPage + 2) + search
        break
        case 'default':
          //window.location.href = this.URLbase + '/' + (this.state.curPage + 2) + search
          if(this.state.curPage < this.state.totalPages) this.state.curPage++
          if(this.state.loggedIn) this.state.fetchUserLinks(this.state.loggedinUserID)
          history.pushState(null,null,this.URLbase + '/' + (this.state.curPage + 1))
        break
        case 'track':
          window.location.href = this.URLbase + '/track/' + this.decToAlpha(this.state.curTrack) + '/' +(this.state.curPage + 2) + search
        break
      }
    },
    regressPage(){
      let search = this.state.search.string ? ('/' + (this.state.search.string)) : ''
      switch(this.state.mode){
        case 'u':
          window.location.href = this.URLbase + '/u/' + this.state.user.name + '/' + this.state.curUserPage + search
        break
        case 'default':
          //window.location.href = this.URLbase + '/' + this.state.curPage + search
          if(this.state.curPage)this.state.curPage--
          if(this.state.loggedIn) this.state.fetchUserLinks(this.state.loggedinUserID)
          if(this.state.curPage){
            history.pushState(null,null,this.URLbase + '/' + (this.state.curPage + 1))
          }else{
            history.pushState(null,null,this.URLbase)
          }
        break
        case 'track':
          window.location.href = this.URLbase + '/track/' + this.decToAlpha(this.state.curTrack) + '/' +(this.state.curPage + 2) + search
        break
      }
    },
    copy(val){
      let copyEl = document.createElement('div')
      copyEl.innerHTML = this.URLbase + '/' + val
      copyEl.style.opacity = .01
      copyEl.style.position = 'absolute'
      document.body.appendChild(copyEl)
      var range = document.createRange()
      range.selectNode(copyEl)
      window.getSelection().removeAllRanges()
      window.getSelection().addRange(range)
      document.execCommand("copy")
      window.getSelection().removeAllRanges()
      copyEl.remove()
      let el = document.querySelector('#copyConfirmation')
      el.style.display = 'block';
      el.style.opacity = 1
      let reduceOpacity = () => {
        if(+el.style.opacity > 0){
          el.style.opacity -= .02 * 2
          if(+el.style.opacity<.1){
            el.style.opacity = 1
            el.style.display = 'none'
          }else{
            setTimeout(()=>{
              reduceOpacity()
            }, 10)
          }
        }
      }
      setTimeout(()=>{reduceOpacity()}, 250)
    },
    closePrompts(){
      this.state.showLoginPrompt = false
      this.state.userSettingsVisible = false
    },
    closeModal(){
      if(this.state.modalQueue.length){
        this.state.modalContent = this.state.modalQueue.shift()
      }else{
        this.state.showModal = false
        this.state.modalContent = ''
      }
    },
    getAvatar(id){
      //if(typeof this.state.userInfo[id] == 'undefined' || !this.state.userInfo[id].avatar){
        return this.state.loggedIn ? this.state.loggedInUser.avatar : this.state.defaultAvatar
      //} else {
      //  this.state.userInfo[id].avatar = this.state.userInfo[id].avatar.replace(' ','')
      //  return this.state.userInfo[id].avatar
      //}
    },
    closePreview(){
      this.state.showPreview = false
      this.state.previewLink = null
    },
    checkEnabled(){
      if(this.state.loggedinUserName) {
        let sendData = {
          userName: this.state.loggedinUserName, passhash: this.state.passhash,
        }
        fetch('checkEnabled.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        })
        .then(res => res.json())
        .then(data => {
          if(!!(+data[0])){
            console.log('logged in.')
            this.state.loggedIn= true
            this.state.loggedinUserID = +data[1]
            this.state.loggedInUser.avatar = data[2]
            this.state.username = this.state.regusername || this.state.loggedinUserName
            //this.state.fetchUserData(this.state.loggedinUserID)
            this.setCookie()
            this.state.loginPromptVisible = false
            this.state.invalidLoginAttemp = false
            //this.state.userInfo[this.state.loggedinUserID] = {}
            //this.state.userInfo[this.state.loggedinUserID].name = this.state.regusername || this.state.loggedinUserName
            //this.state.userInfo[this.state.loggedinUserID].avatar = data[2]
            //this.state.userInfo[this.state.loggedinUserID].isAdmin = +data[3]
            if(+data[3]) this.state.isAdmin = true
            this.fetchUserLinks(this.state.loggedinUserID)
            //this.state.maxResultsPerPage = +data[4]
          }else{
            console.log('not logged in.')
            this.state.loadingAssets = false
            this.state.loggedIn= false
            this.state.loggedinUserName = ''
            this.state.loggedinUserID = ''
            this.state.passhash = ''
            this.state.isAdmin = false
            this.state.invalidLoginAttempt = true
          }
          this.getMode()
        })
      }
    },
    setLinksOwner(){
      if(!this.state.links.length) return
      let sendData = {
        userName: this.state.username,
        passhash: this.state.passhash,
        ids: JSON.parse(JSON.stringify(this.state.links)).map(v=>{ return v.id})
      }
      fetch('setOwner.php',{
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(sendData),
      }).then(res => res.json()).then(data=>{
        console.log('res from setOwner.php: ', data)
        if(!data[0]) alert('error setting link owner')
      })
    },
    fetchUserLinks(userID){
      if(this.state.loggedinUserName) {
        let sendData = {
          userID,
          page: this.state.curPage,
          maxResultsPerPage: this.state.maxResultsPerPage
        }
        fetch('fetchUserLinks.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        })
        .then(res => res.json())
        .then(data => {
          this.state.loadingAssets = false
          if(!!(+data[0])){
            this.state.userLinks = []
            this.state.links = []
            data[1].map((v, i) => {
              let obj = {
                size: +data[2][i].size,
                type: data[2][i].type,
                selected: false,
                ct: i,
                href: v,
                userID: +data[2][i].userID,
                id: +data[2][i].id,
                slug: data[2][i].slug,
                originalSlug: data[2][i].originalSlug,
                origin: data[2][i].origin,
                date: data[2][i].date,
                linkType: 'userLink',
                serverTZO: data[2][i].serverTZO,
                views: data[2][i].views
              }
              this.state.userLinks.push(obj)
            })
            this.state.totalPages = +data[3]
            if(this.state.curPage+1 > this.state.totalPages) this.lastPage()
          }
        })
      }
    },
    selectAll(){
      this.state.links.map(v=>{
        v.selected = true
      })
      this.state.userLinks.map(v=>{
        v.selected = true
      })
    },
    deSelectAll(){
      this.state.links.map(v=>{
        v.selected = false
      })
      this.state.userLinks.map(v=>{
        v.selected = false
      })
    },
    deleteSelected(){
      let count = 0
      let linksToProcess = []
      let userLinksToProcess = []
      let slugs = []
      this.state.links.map((v, i) => {
        if(v.selected){
          count++
          linksToProcess = [...linksToProcess, v.id]
          slugs = [...slugs, v.slug]
        }
      })
      this.state.userLinks.map((v, i) => {
        if(v.selected){
          count++
          userLinksToProcess = [...userLinksToProcess, v.id]
          slugs = [...slugs, v.slug]
        }
      })
      if(!count) return
      if(prompt(`\n\nARE YOU SURE YOU WANT TO DELETE ${count} ITEM${count>1?'S':''}?\n\n\n   >>> THIS ACTION CANNOT BE UNDONE! <<<\n\n\n  type 'yes' to continue"`).toLowerCase().indexOf('yes') != -1){
        console.log('deleting selected...')
        let sendData = {
          userName: this.state.username,
          passhash: this.state.passhash,
          slugs
        }
        console.log('sendData', sendData)
        fetch('delete.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        })
        .then(res => res.json()).then(data => {
          console.log(data)
          if(data[0]){
            this.state.links = this.state.links.filter((v, i) => !linksToProcess.filter(q => q == v.id).length)
            this.state.userLinks = this.state.userLinks.filter((v, i) => !userLinksToProcess.filter(q => q == v.id).length)
            console.log(`deleted ${count} items`)
            //this.state.links = []
            //this.fetchUserLinks(this.state.loggedinUserID)
          }else{
            alert(`there was a problem deleting ${slugs.length > 1 ? 'these' : 'this'} asset${slugs.length > 1 ? 's' : ''}`)
          }
        })
      }
    },
    setCookie() {
      let cookies = document.cookie
      cookies.split(';').map(v=>{
        if(v.indexOf('autoplay')==-1){
          document.cookie = v + '; expires=' + (new Date(0)).toUTCString() + '; path=/; domain=' + this.state.rootDomain
        }
      })
      document.cookie = 'loggedinuser=' + this.state.loggedinUserName + '; expires=' + (new Date((Date.now()+3153600000000))).toUTCString() + '; path=/; domain=' + this.state.rootDomain
      document.cookie = 'loggedinuserID=' + this.state.loggedinUserID + '; expires=' + (new Date((Date.now()+3153600000000))).toUTCString() + '; path=/; domain=' + this.state.rootDomain
      document.cookie = 'token=' + this.state.passhash + '; expires=' + (new Date((Date.now()+3153600000000))).toUTCString() + '; path=/; domain=' + this.state.rootDomain
      document.cookie = 'autoplay=' + this.state.autoplay + '; expires=' + (new Date((Date.now()+3153600000000))).toUTCString() + '; path=/; domain=' + this.state.rootDomain
      document.cookie = 'showControls=' + this.state.showControls + '; expires=' + (new Date((Date.now()+3153600000000))).toUTCString() + '; path=/; domain=' + this.state.rootDomain
    },
    getPages(){
      console.log('loaded. mode: ' + this.state.mode)
    },
    login(){
      let sendData = {userName: this.state.username, password: this.state.password}
      fetch('login.php',{
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(sendData),
      })
      .then(res => res.json())
      .then(data => {
        if(data[0]){
          this.state.modalContent = ''
          this.state.showModal = false
          console.log('login succeeded!')
          this.state.loggedIn= true
          this.state.loggedinUserName = this.state.username
          this.state.loggedinUserID = +data[2]
          //this.state.fetchUserData(this.state.loggedinUserID)
          this.state.isAdmin = +data[4]
          this.state.passhash = data[1]
          this.setCookie()
          this.closePrompts()
          this.state.invalidLoginAttemp = false
          this.state.loggedInUser.avatar = data[3]
          this.setLinksOwner()
          this.fetchUserLinks(this.state.loggedinUserID)
          this.state.links = []
        }else{
          console.log('not logged in.')
          this.state.loggedIn= false
          this.state.invalidLoginAttempt = true
        }
      })
    },
    getMode(){
      let vars = window.location.pathname.split('/').filter(v=>v)
      console.log(vars)
      if(vars.length>0){
        switch(vars[0]){
          case 'd':
            this.state.mode = 'single'
            //this.state.curPage = (+vars[1])-1
            //this.state.viewDemo = this.alphaToDec(vars[1])
            //this.state.rawDemoID = vars[1]
            //this.$nextTick(()=>this.loadDemo(this.alphaToDec(vars[1])))
            //if(vars[2]){
            //  this.state.search.string = decodeURIComponent(vars[2])
           //}
            break
          case 'u':
            /*if(!vars[1]) window.location.href = window.location.origin
            this.state.viewAuthor = decodeURIComponent(vars[1]);
            this.state.user = {name: decodeURIComponent(vars[1])}
            this.state.mode = 'user'
            if(vars[2]){
              this.state.curUserPage = (+vars[2])-1
              if(vars[3]){
                this.state.search.string = decodeURIComponent(vars[3])
                search = '/' + vars[3]
                history.pushState(null,null,this.URLbase + '/u/' + (this.state.user.name) + '/' + (this.state.curPage + 1)) + search
                this.beginSearch()
              }else{
                if(!this.state.curUserPage || this.state.curUserPage < 0 || this.state.curUserPage > 1e6) this.state.curUserPage = 0
                history.pushState(null,null,this.URLbase + '/u/' + (vars[1]) + ((this.state.curUserPage) ? '/' + (this.state.curUserPage + 1) : ''))
                this.getPages()
              }
            } else {
              this.state.curUserPage = 0
              history.pushState(null,null,this.URLbase + '/u/' + (vars[1]) + ((this.state.curUserPage) ? '/' + (this.state.curUserPage + 1) : ''))
              this.getPages()
            }*/
          break
          default:
            this.state.mode = 'default'
            let search = ''
            if(vars[0]){
              this.state.curPage = (+vars[0])-1
              if(vars[1]){
                this.state.search.string = decodeURIComponent(vars[1])
                search = '/' + vars[1]
                //history.pushState(null,null,this.URLbase + '/' + (this.state.curPage + 1)) + search
                //this.beginSearch()
                this.state.curPage = 0
                this.state.jumpToPage(0)
              }else{
                history.pushState(null,null,this.URLbase + '/' + this.state.curPage ? (this.state.curPage + 1) : '')
                if(!this.state.curPage || this.state.curPage < 0 || this.state.curPage > 1e6) this.state.curPage = 0
                this.fetchUserLinks(this.state.loggedinUserID)
              }
            }else{
              window.location.href = this.URLbase
            }
          break
        }
      }else{
        this.state.mode = 'default'
        this.getPages()
        if(window.location.href !== this.URLbase + '/') window.location.href = window.location.origin
      }
    },
    logout(){
      history.pushState(null,null,this.URLbase)
      let cookies = document.cookie
      cookies.split(';').map(v=>{
        if(v.indexOf('autoplay')==-1){
          document.cookie = v + '; expires=' + (new Date(0)).toUTCString() + '; path=/; domain=' + this.state.rootDomain
        }
      })
      //if(this.state.search.string != '') this.state.search.demos = this.state.search.demos.filter(v=>!v.private)
      switch(this.state.mode){
        case 'user':
        //this.state.user.demos = this.state.user.demos.filter(v=>!v.private)
        break
        case 'single':
        //this.state.demos = this.state.demos.filter(v=>!v.private)
        break
        case 'default':
        //this.state.landingPage.demos = this.state.landingPage.demos.filter(v=>!v.private)
        break
      }
      this.state.loggedIn= false
      this.state.isAdmin = false
      this.state.loggedinUserID = this.state.loggedinUserName = ''
      window.location.reload()
    },
    checkLogin(){
      let l = (document.cookie).split(';').filter(v=>v.split('=')[0].trim()==='loggedinuser')
      if(l.length){
        this.state.loggedinUserName = l[0].split('=')[1]
        this.state.username = this.state.loggedinUserName
        let l2 = (document.cookie).split(';').filter(v=>v.split('=')[0].trim()==='token')
        if(l2.length){
          this.state.passhash = l2[0].split('=')[1]
          let l3 = (document.cookie).split(';').filter(v=>v.split('=')[0].trim()==='loggedinuserID')
          if(l3.length){
            this.state.loggedinUserID = +l3[0].split('=')[1]
            this.checkEnabled()
          }
        }
      } else {
        this.state.loadingAssets = false
        this.getMode() 
      }
      //this.checkShowControlsPref()
      //this.checkAutoplayPref()
      //this.checkExactSearchPref()
    }
  },
  watch: {
    'state.loadingAssets' (val){
      if(val){
        this.state.modalContent = '<div style="width: 500px; height: 100px; position:absolute; text-align: center;font-size: 24px; color: white; top: 50%; left: 50%; transform: translate(-50%, -50%);">... loading ...</div>'
        this.state.showModal = true
      }else{
        this.state.modalContent = ''
        this.state.showModal = false
      }
    },
    'state.uploadInProgress' (val) {
      /*console.log('state.uploadInProgress val', val)
      if(val){
        this.state.modalContent = `
          loading...
        `
        this.state.showModal = true
      }else{
        this.state.modalContent = ''
        this.state.showModal = false
      }
      */
    }
  },
  computed:{
    URLbase(){
      let ret = window.location.origin
      if(ret.toLowerCase().indexOf(this.state.rootDomain) === -1){
        ret += '/imjur'
      }
      return ret
    }
  },
  mounted(){
  
    window.onmousedown = e => {
      this.state.keys[18] = false
    }
  
    window.onkeyup = e =>{
      e.preventDefault()
      e.stopPropagation()
      this.state.keys[e.keyCode] = false
      this.state.keys[18] = false
    }
    
    window.onkeydown = e =>{
      this.state.keys[e.keyCode] = true
      console.log(e.keyCode)
      if(this.state.keys[18]){
        e.preventDefault()
        e.stopPropagation()
      }
      switch(e.keyCode){
        case 116:
          window.location.reload()
        break
        case 65:
          if(this.state.keys[17]){
            e.preventDefault()
            e.stopPropagation()
            if(this.state.keys[16]){ // ctrl + shift + a
              this.deSelectAll()
            }
            if(!this.state.keys[16]){ // ctrl + a
              this.selectAll()
            }
          }
        break
        case 46:
          this.deleteSelected()
        break
        case 37: // left arrow
          if(this.state.showPreview){
            this.prev()
          }else{
            if(this.state.keys[18] && this.state.curPage) this.regressPage()
          }
        break
        case 39: // right arrow
           if(this.state.showPreview){
            this.next()
          }else{
            if(this.state.keys[18] && this.state.curPage < this.state.totalPages) this.advancePage()
          }
        break
        case 36: // home
          if(this.state.keys[18]) this.firstPage()
        break
        case 35: // end
          if(this.state.keys[18]) this.lastPage()
        break
        case 27:
          this.state.showPreview = false
          if(!this.state.uploadInProgress && !this.state.showLoginPrompt) this.state.showModal = false
          this.closePrompts()
        break
      }
    }
    this.state.closeModal = this.closeModal
    this.state.prev = this.prev
    this.state.next = this.next
    this.state.copy = this.copy
    this.state.login = this.login
    this.state.logout = this.logout
    this.state.URLbase = this.URLbase
    this.state.register = this.register
    this.state.lastPage = this.lastPage
    this.state.getPages = this.getPages
    this.state.firstPage = this.firstPage
    this.state.getAvatar = this.getAvatar
    this.state.selectAll = this.selectAll
    this.state.setCookie = this.setCookie
    this.state.jumpToPage= this.jumpToPage
    this.state.checkLogin = this.checkLogin
    this.state.advancePage = this.advancePage
    this.state.regressPage = this.regressPage
    this.state.deSelectAll = this.deSelectAll
    this.state.closePrompts = this.closePrompts
    this.state.closePreview = this.closePreview
    this.state.setLinksOwner = this.setLinksOwner
    this.state.fetchUserLinks = this.fetchUserLinks
    this.state.deleteSelected = this.deleteSelected
    this.state.showUserSettings = this.showUserSettings
    this.checkLogin()
  }
}
</script>

<style>
/* latin-ext */
@font-face {
  font-family: 'Courier Prime';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(https://fonts.gstatic.com/s/courierprime/v9/u-450q2lgwslOqpF_6gQ8kELaw9pWt_-.woff2) format('woff2');
  unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Courier Prime';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(https://fonts.gstatic.com/s/courierprime/v9/u-450q2lgwslOqpF_6gQ8kELawFpWg.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
body,html{
  background-color: #000;
  background-image: url(./assets/new_bg.jpg);
  background-repeat: repeat;
  background-size: cover;
  background-position: center center;
  margin: 0;
  height: 100vh;
  min-width: 600px;
  overflow: hidden;
  font-family: Courier Prime;
}
a:visited{
  color: #40f;
}
button:focus{
  outline: none;
}
button{
  font-size: 18px;
  border: 2px solid #0008;
  border-radius: 6px;
  background: #adcc;
  padding: 4px;
  padding-left: 10px;
  padding-right: 10px;
  font-weight: 900;
  min-width: 200px;
  cursor: pointer;
  font-family: Courier Prime;
  color: #000;
  text-shadow: 1px 1px 3px #40f;
}
a{
  text-decoration: none;
  color: #08f;
}
.copyButton{
  display: inline-block;
  width: 30px;
  height: 30px;
  background-image: url(./assets/link.png);
  cursor: pointer;
  z-index: 500;
  background-size: 90% 90%;
  left: calc(50% + 180px);
  background-position: center center;
  background-repeat: no-repeat;
  border: none;
  background-color: #8fcc;
  margin: 5px;
  border-radius: 5px;
  vertical-align: middle;
}
#copyConfirmation{
  display: none;
  position: absolute;
  width: 100vw;
  height: 100vh;
  top: 0;
  left: 0;
  background: #012d;
  color: #8ff;
  opacity: 1;
  text-shadow: 0 0 5px #fff;
  font-size: 46px;
  text-align: center;
  z-index: 10000;
}
#innerCopied{
  position: absolute;
  top: 50%;
  width: 100%;
  z-index: 1020;
  text-align: center;
  transform: translate(0, -50%) scale(2.0, 1);
}
.resultLink{
  text-decoration: none;
  color: #fff;
  background: #4f86;
  padding: 10px;
  display: inline-block;
}
#resultDiv{
  position: absolute;
  margin-top: 50px;
  left: 50%;
  transform: translate(-50%);
}
.input{
  text-align: center;
  font-size: 24px;
  background: #0004;
  border: none;
  border-bottom: 2px solid #2fa;
  width: 300px;
  color: #ffa;
}
input[type=text]{
  font-size: 24px;
  background: #0004;
  border: none;
  border-bottom: 2px solid #2fa;
  width: 300px;
  color: #ffa;
}
input[type=checkbox]{
  transform: scale(1.5);
  margin: 8px;
  margin-left: 5px;
}
input:focus{
  outline: none;
}
button:focus{
  outline: none;
}
select:focus{
  outline: none;
}
/* Customize the label (the checkboxLabel) */
.checkboxLabel {
  padding-left: 35px;
  margin-bottom: -2px;
  cursor: pointer;
  margin: 0;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  text-align: left;
  position: absolute;
  margin-left: 30px;
}

/* Hide the browser's default checkbox */
.checkboxLabel input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  cursor: pointer;
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  border: 1px solid #2468;
  background-color: #123;
  border-radius: 5px;
}

/* On mouse-over, add a grey background color */
.checkboxLabel:hover input ~ .checkmark {
  background-color: #234;
}

/* When the checkbox is checked, add a blue background */
.checkboxLabel input:checked ~ .checkmark {
  background-color: #208;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.checkboxLabel input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.checkboxLabel .checkmark:after {
  left: 7px;
  top: 1px;
  width: 7px;
  height: 16px;
  border: solid white;
  border-width: 0 5px 5px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
::-webkit-scrollbar {
  width: 20px;
}

::-webkit-scrollbar-track {
  background: #204; 
}
 
::-webkit-scrollbar-thumb {
  background: #308;
  border-radius: 20px;
}

::-webkit-scrollbar-thumb:hover {
  background: #60f; 
}
</style>
