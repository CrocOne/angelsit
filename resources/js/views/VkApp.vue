<template>
  <div class="walls">
    <div class="loading" v-if="loading">
      Loading...
    </div> 
 
	<div class="album py-5 bg-light"  v-if="walls">
        <div class="container">
		<pagination :data="walls" @pagination-change-page="getResults"></pagination>
          <div class="row">		 		  
            <div class="col-md-4"  v-for="{ text, link, image } in walls.items">
              <div class="card mb-4 shadow-sm">			 				 
                <img class="card-img-top" style="height: 225px; width: 100%; display: block;" :src="image">
                <div class="card-body">					                 
				  <a :href="link">                   
					<p class="card-text">{{text}}</p>				  				   				   
				  </a>				  
                  <div class="d-flex justify-content-between align-items-center">                          
                  </div>
                </div>
              </div>
            </div>     			
          </div>		
		<pagination :data="walls" @pagination-change-page="getResults"></pagination>		  
        </div>
	</div>	
  </div>
</template>
<script> 
import axios from 'axios';
export default {
  data() {
    return {
      loading: false,
      walls: null,
      error: null,
    };
  },
  mounted() {	 
	this.getResults();
  },
  methods: {
    getResults(page=1) {
      this.error = this.walls = null;
      this.loading = true;
      axios
        .get('/api/walls?page='+ page)
        .then(response => {
			this.loading = false;
			console.log(response);
			this.walls = response.data;
        }).catch(error => {
			this.loading = false;
			this.error = error.response.data.message || error.message;
		});
    }
  }
}
</script>