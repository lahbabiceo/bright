<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import topbar from './components/TopBar.vue'
import mainmenu from './components/MainMenu.vue'
</script>

<template>


   

    
        <!-- <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
        <button class="btn btn-primary" >Hey Guys</button> -->
      <div >
        <topbar></topbar>

        <div class="main-container">

          <div class="side-menu">
              <mainmenu></mainmenu>
          </div>

          <div class="main-body" >
              <RouterView />
          </div>

        </div>
      </div>

</template>

<style scoped>

@media (min-width: 1024px) {
  
}
.body-container, .main-container{
  display: flex;
  width:100%
}
.main-body{
 
  flex-grow: 1
}
.main-body{
      background: #f4f4f4;
}
</style>
<style>
  @import "https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css";
  @import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css";
  @import "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css";
  @import "https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap";
</style>