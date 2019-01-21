<template>
    <div class="container">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-dark text-white">
                <h3 class="card-title">Your CRM Users</h3>

                <div class="card-tools">
                  <button class="btn btn-warning" @click="newUserModal">Create User <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header | npm install axios vform -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Created</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="user in users" :key="user.id">
                    <td><strong>{{user.name | capitalName}}</strong></td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | capitalName}}</td>
                    <td><small>{{user.created_at | customDate}}</small></td>
                    <td>
                        <a href="#" @click="editUserModal(user)"><i class="fas fa-pen icon-green"></i></a>
                        <a href="#" @click="deleteUser(user.id)"><i class="fas fa-minus-circle icon-red"></i></a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
                <!-- Add user as family Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title" id="addUserModal">Add User</h5>
                        <h5 v-show="editMode" class="modal-title" id="addUserModal">Update User Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('name')}" placeholder="Name">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.email" type="text" name="email" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('email')}" placeholder="Email">
                            <has-error :form="form" field="email"></has-error>
                        </div>

                        <div class="form-group">
                            <select v-model="form.type" name="type" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('type')}">
                                <option value="" selected disabled hidden>User Type</option>
                                <option value="user">User</option>
                                <option value="author">Author</option>
                                <option value="admin">Admin</option>
                            </select>
                            <has-error :form="form" field="access_level"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.password" type="password" name="password" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('password')}" placeholder="Password">
                            <has-error :form="form" field="password"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-show="!editMode" class="btn btn-primary" type="submit">Add User</button>
                        <button v-show="editMode" class="btn btn-primary" type="submit">Update User</button>
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--modal ending-->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editMode: false,
                users: {},//object to hold clients data that comes back from database
                //create new instance of vform and specify fields
                //fields will have two way data binding, hence aiding backend validation
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: ''
                })
            }
        },
        methods: {
            getUsers(){
                //get the clients from the controller using axios
                //use a promise to assign the returned data to the clients object
                //use data.data cos response would come wrapped inside a data object
                axios.get('api/user').then(({data}) => {this.users = data.data});
            },
            createUser(){
                this.$Progress.start();//trigger vue progress bar to start
                //make post request to the necessary endpoint
                this.form.post('api/user')
                    .then(() => {
                        //success case
                        Fire.$emit('refresh');//define custom event that is fired everytime a client is created
                        $('#addUserModal').modal('hide');//close the modal after client creation
                        toast({
                            type: 'success',
                            title: 'User created'
                        });
                        this.$Progress.finish();//trigger vue progress bar to finish
                        })
                    .catch(() => {
                        //error case
                        toast({
                            type: 'error',
                            title: 'something went wrong'
                        });
                        this.$Progress.fail();//fail progress bar
                    });
            },
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                    .then(()=>{
                        Fire.$emit('refresh');
                        $('#addUserModal').modal('hide');
                        swal('Updated!','Info updated.','success');
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        toast({
                            type: 'error',
                            title: 'something went wrong'
                        });
                        this.$Progress.fail();
                    });
            },
            deleteUser(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    //make delete request to endpoint
                    if(result.value){
                        this.form.delete('api/user/'+id)
                        .then(()=>{
                                swal('Deleted!','User deleted.','success');
                                Fire.$emit('refresh');
                        })
                        .catch(()=>{
                            swal('Failed!','Unable to delete user.','warning');
                        });
                    }
                });
            },
            newUserModal(){
                this.editMode = false;//toggle editmode to false
                this.form.reset();//clear all previous client data
                $('#addUserModal').modal('show');
            },
            editUserModal(user){
                this.editMode = true;//toggle editmode to true
                this.form.reset();
                $('#addUserModal').modal('show');
                this.form.fill(user);//fill modal fields with client data
            }
        },
        created() {
            this.getUsers();//will get all clients once component is created
            //query database every 3 seconds for new data
            /*first method - not ideal due to performance issues
            setInterval(() => {
                this.getClients();
            }, 3000);*/

            //second method
            Fire.$on('refresh',() => {
                this.getUsers();
            });//listen for this event and use this callback
        }
    }
</script>
