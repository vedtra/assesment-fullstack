<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link :to="{name: 'home'}" class="navbar-brand">Home</router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto" v-if="!this.$store.state.isLoggedIn">
          <li class="nav-item">
           <router-link :to="{ name: 'login' }" class="nav-link">Login</router-link>
          </li>
      </ul>
      <ul class="navbar-nav mr-auto" v-if="!this.$store.state.isLoggedIn">
          <li class="nav-item">
           <router-link :to="{ name: 'register' }" class="nav-link">Register</router-link>
          </li>
      </ul>
      <ul class="navbar-nav ml-auto" v-if="this.$store.state.isLoggedIn">
        <li class="nav-item">
         <a href="#" class="" @click="logout()">Logout</a>
        </li>
      </ul>
    </div>
  </nav>
</template>
<script>
    export default {
        methods: {
            logout() {
                this.axios.get('user/logout?token=' + this.$store.state.token).then(response => {
                    if(response.data.success == true)
                    {
                        // login user, store the token and redirect to dashboard
                        this.$store.commit('LogoutUser')
                        this.$router.push({name: 'login'})
                    }
                }).catch(error => {
                    if (error.response.status==401 || error.response.status==422 )
                    {
                        this.$store.commit('LogoutUser')
                        this.$router.push({name: 'login'})
                    }
                });
            }
        }
    }
</script>


<style>
.navbar {
  margin-bottom: 30px;
}
</style>