<template>
    <div class="container">
        <div class="row">
          <div class="col-12 mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>

                <div class="card-tools">
                <button class="btn btn-success" @click='newModal'>New User <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Registered At</th>
                      <th>Modify</th>
                    </tr>
                 
               
                    <tr v-for='user in users':key='user.id' >
                      <td>{{user.id}}</td>
                      <td>{{user.name}}</td>
                      <td>{{user.email}}</td>
                      <td>{{user.type | upText }}</td>
                       <td>{{user.created_at | mydate}}</td>
                      <td>
                      <a href="#" @click='editModal(user)'>
                      <i class='fas fa-edit'></i>
                      </a>|
                      <a href="#">
                      <i class='fas fa-trash red' @click='deleteUser(user.id)'></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      <!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show='editMode' class="modal-title" id="exampleModalLabel">Update User Info</h5>
        <h5 v-show='!editMode' class="modal-title" id="exampleModalLabel">New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

  <form @submit.prevent="editMode ? updateUser() : createUser()">
      <div class="modal-body">
        <div class="form-group">  
      <input v-model="form.name" type="text" name="name" placeholder='Enter Name'
        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
      <has-error :form="form" field="name"></has-error>
    </div>

     <div class="form-group">
      <input v-model="form.email" type="email" email="email" placeholder='Enter Email'
        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>
    </div>

    <div class="form-group">
      <textarea v-model="form.bio" name="bio" id="bio" placeholder='Short Bio for user'
        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
      <has-error :form="form" field="bio"></has-error>
    </div>

    <div class="form-group">
      <select v-model="form.type" name="type" id="type" 
        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
         <option value=''>Select user Role</option>
         <option value='admin'>Admin</option>
          <option value='user'>Standard User</option>
           <option value='author'>Author</option>
        </select>
      <has-error :form="form" field="type"></has-error>
    </div>

   <div class="form-group">
      <input v-model="form.password" type='password' name="password" id="password" placeholder='Enter Password'
        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
      <has-error :form="form" field="password"></has-error>
    </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button v-show='editMode' type="submit" class="btn btn-success">Update User</button>
         <button v-show='!editMode' type="submit" class="btn btn-primary">Save User</button>
      </div>
      </form>
    </div>
  </div>
</div>
 <!-- end Modal -->
    </div>
</template>

<script>
    export default {
      
      data(){
       
        return{
          editMode:false,
           users:{},
          form: new Form({
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
      methods:{
      updateUser(){
        this.$Progress.start();
        this.form.put('api/user/'+this.form.id)
        .then(()=>{         
          $('#addNew').modal('hide');
            Swal.fire(
      'Updated!',
      'Information Updated Successfully.',
      'success'
    )
     Fire.$emit('afterCreate');
    this.$Progress.finish();
        })
        .catch(()=>{
           this.$Progress.fail();
        })
           //console.log('editting data');
      },

        editModal(user){
          this.editMode=true;
          this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(user);
        },
         newModal(){
           this.editMode=false;
          this.form.reset();
           $('#addNew').modal('show');
        }, 
        deleteUser(id){
          Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {

 if (result.value) {
//send ajax request to the serve
this.form.delete('api/user/'+id)
.then(()=>{
    Swal.fire(
      'Deleted!',
      'Your file has been deleted.',
      'success'
    )
  Fire.$emit('afterCreate');
})
 
.catch(()=>{
    swal('failed!', "There was something wrong","warning");
  });
}
  
})

        },

        loadUsers(){
          axios.get('api/user')
          .then(({data})=>(this.users=data.data));

        },
        
        createUser(){
          //submit data via post to the api
           this.$Progress.start();
          this.form.post('api/user')
          .then(()=>{
             Fire.$emit('afterCreate');
         $('#addNew').modal('hide')
              Toast.fire({
              icon: 'success',
              title: 'User Created successfully'
            })
           this.$Progress.finish();

          })
          .catch(()=>{
             this.$Progress.fail();
          })
         
        }
      },
        created() {
           this.loadUsers();
           Fire.$on('afterCreate',()=>{
             this.loadUsers();
           });
        }
    }
</script>
