<script setup>
defineProps({
  msg: {
   
  }
})

</script>
<template>

  <div class="topbar pt-1 sticky-top">
    <section class="container-fluid">
  
  <div class="row ">
    <div class="col-2">
      <button class="btn btn-transparent">
          <i class="fa fa-bars" aria-hidden="true"></i>
      </button>
      <img src="../assets/img/Logo.png" class="logo" />
    </div>

    <div class="col-6">

       <div class="input-group">
            <input class="form-control border-end-0 border rounded-pill" type="text" placeholder="Search Client, Invoice, Company, Contact, Projects..."  id="example-search-input">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </span>
     </div>
          
    </div>

    <div class="col-4">

     <div class="row">
      <div class="col-1">
        <button class="btn btn-transparent">
            <i class="bi-cart-check"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success text-light">
              9
            </span>
        </button>
      </div>

      <div class="col-1">
        <button class="btn btn-transparent">
            <i class="bi-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light">
              99+
            </span>
        </button>
      </div>

    <div class="col-sm ml-5 text-right">
      <span> 
        <button class="btn btn-transparent">
        
       
          
        </button>
      </span>
    </div>
    </div>
  
     

     <div class="btn-group" @click="!show">
        <button type="button" class="btn btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         
          <span>Tariq Abdullah </span>
          <i class="bi-person-circle"></i>
        </button>
        <div class="dropdown-menu show">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div>



      



    </div>
  </div>
          <!-- Search form -->

          
  <!-- Navbar -->
    </section>
  </div>
</template>

<style scoped>
.topbar{
  display:flex;
  flex-direction: row;
  width:100%;
  flex-basis: 100%;
  height:50px; 
  border-bottom: var(--light-border);
  background: var(--white-bg);
}
.logo{
  max-width: 100px;
}
</style>
