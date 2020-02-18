
<style>
.widget-user-header{
    background-position:center center;
    background-size:cover;
     height: 250px;
     background-color:cyan;

}
.head{
  text-align:centre;
}
</style>

<template>
    <div class="container">
      <div class='row'>
            <div class="col-md-12 mt-3">
                <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background:url('./img/background.png')">
              <h3 class="widget-user-username">Elizabeth Pierce</h3>
              <h5 class="widget-user-desc">Web Designer</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">3,200</h5>
                    <span class="description-text">SALES</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">13,000</h5>
                    <span class="description-text">FOLLOWERS</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">PRODUCTS</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
           
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">
                    <!-- Post -->
                    <div class="post">
                     <h1 id='head'> display users activities</h1>
                    </div>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane active" id="settings">
                    <form class="form-horizontal">
                    
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-12 col-form-label">Name</label>
                        <div class="col-sm-12">
                          <input v-model="form.name" type="email" class="form-control" id="name" placeholder="Name" 
                          :class="{ 'is-invalid': form.errors.has('name') }">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-12 col-form-label">Email</label>
                        <div class="col-sm-12">
                          <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Email" 
                          :class="{ 'is-invalid': form.errors.has('email') }">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-12 col-form-label">Experience</label>
                        <div class="col-sm-12">
                          <textarea v-model="form.bio" class="form-control" id="experience" placeholder="Experience" 
                          :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                        </div>
                      </div>

                      <div class="form-group row">
                      <label for="photo" class="col-sm-2 col-form-label">Profile Photo</label>
                      <div class="co-sm-12">
                      <input type="file" @change='updateProfile' name="photo" class="form-input" 
                      :class="{ 'is-invalid': form.errors.has('photo') }">
                      </div>
                      </div>

                      <div class="form-group row">
                        <label for="photo" class="col-sm-12 col-form-label">Password (Leave empty if not changing)</label>
                        <div class="col-sm-12">
                          <input v-model="form.password" type="password" class="form-control" id="password" placeholder="password"
                          :class="{ 'is-invalid': form.errors.has('password') }">
                        </div>
                      </div>

                      
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button @click.prevent='updateInfo' class="btn btn-success">Update</button>
                        </div>
                      </div> 
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>


            </div>
  </div>
    </div>
</template>

<script>
    export default {
      data(){
        return{
          form:new Form({
            id:'',
             name:'',
             email:'',
             password:'',
             type:'',
             bio:'',
             photo:''
         })
        }
      },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            updateProfile(e){
            //console.log('The button is clicked');
            //image encoding
            let file=e.target.files[0];
           console.log(file);
          
            let reader=new FileReader();
             if (file['size'] < 2111775){
            reader.onload=(file)=>{
             ///   console.log('RESULT',reader.result)
             this.form.photo= reader.result;
            }
             reader.readAsDataURL(file);
            }else{
                      Swal({type:'error',
                            title: 'oops......',
                            text:'You are uploading very large file',
                      });
            }
            }, 

            updateInfo(){
                this.$Progress.start();
               this.form.put('api/profile/')
               .then(()=>{
                 this.$Progress.finish();
               })
               .catch(()=>{
                  this.$Progress.fail();
               });

        }


        },
       
        created(){
          //Sending an http request to server to fill the form
          axios.get('api/profile')
          .then(({data})=>(this.form.fill(data)));

        }
    }
</script>
