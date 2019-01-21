<template>
    <div class="container">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-dark text-white">
                <h3 class="card-title">Client Overview</h3>

                <div class="card-tools">
                  <button class="btn btn-warning" @click="newClientModal">Add Client <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header | npm install axios vform -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th>Created</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="client in clients" :key="client.id">
                    <td>{{client.name | capitalName}}</td>
                    <td>{{client.location}}</td>
                    <td>{{client.email}}</td>
                    <td>{{client.phone_number}}</td>
                    <td>{{client.product}}</td>
                    <td><code>{{client.created_at | customDate}}</code></td>
                    <td>
                        <a href="#" @click="editClientModal(client)"><i class="fa fa-edit icon-green"></i></a>
                        <a href="#" @click="deleteClient(client.id)"><i class="fa fa-trash icon-red"></i></a>
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
        <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateClient() : createClient()">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title" id="addClientModal">Add client to CRM</h5>
                        <h5 v-show="editMode" class="modal-title" id="addClientModal">Update Client Info</h5>
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
                            <input v-model="form.location" type="text" name="location" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('location')}" placeholder="Location">
                            <has-error :form="form" field="location"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.email" type="text" name="email" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('email')}" placeholder="Email">
                            <has-error :form="form" field="email"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.phone_number" type="text" name="phone_number" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('phone_number')}" placeholder="Phone Number">
                            <has-error :form="form" field="phone_number"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.product" type="text" name="product" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('product')}" placeholder="Product">
                            <has-error :form="form" field="product"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-show="!editMode" class="btn btn-primary" type="submit">Add Client</button>
                        <button v-show="editMode" class="btn btn-primary" type="submit">Update Client</button>
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
                clients: {},//object to hold clients data that comes back from database
                //create new instance of vform and specify fields
                //fields will have two way data binding, hence aiding backend validation
                form: new Form({
                    id: '',
                    name: '',
                    location: '',
                    email: '',
                    phone_number: '',
                    product: ''
                })
            }
        },
        methods: {
            getClients(){
                //get the clients from the controller using axios
                //use a promise to assign the returned data to the clients object
                //use data.data cos response would come wrapped inside a data object
                axios.get('api/client').then(({data}) => {this.clients = data.data});
            },
            createClient(){
                this.$Progress.start();//trigger vue progress bar to start
                //make post request to the necessary endpoint
                this.form.post('api/client')
                    .then(() => {
                        //success case
                        Fire.$emit('refresh');//define custom event that is fired everytime a client is created
                        $('#addClientModal').modal('hide');//close the modal after client creation
                        toast({
                            type: 'success',
                            title: 'samaruuuuuu'
                        });
                        this.$Progress.finish();//trigger vue progress bar to finish
                        })
                    .catch(() => {
                        //error case
                        toast({
                            type: 'error',
                            title: 'something went wrong :('
                        });
                        this.$Progress.fail();//fail progress bar
                    });
            },
            updateClient(){
                this.$Progress.start();
                this.form.put('api/client/'+this.form.id)
                    .then(()=>{
                        Fire.$emit('refresh');
                        $('#addClientModal').modal('hide');
                        swal('Updated!','Info has been updated.','success');
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        toast({
                            type: 'error',
                            title: 'something went wrong :('
                        });
                        this.$Progress.fail();
                    });
            },
            deleteClient(id){
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
                        this.form.delete('api/client/'+id)
                        .then(()=>{
                                swal('Deleted!','Client has been deleted.','success');
                                Fire.$emit('refresh');
                        })
                        .catch(()=>{
                            swal('Failed!','Unable to delete client.','warning');
                        });
                    }
                });
            },
            newClientModal(){
                this.editMode = false;//toggle editmode to false
                this.form.reset();//clear all previous client data
                $('#addClientModal').modal('show');
            },
            editClientModal(client){
                this.editMode = true;//toggle editmode to true
                this.form.reset();
                $('#addClientModal').modal('show');
                this.form.fill(client);//fill modal fields with client data
            }
        },
        created() {
            this.getClients();//will get all clients once component is created
            //query database every 3 seconds for new data
            /*first method - not ideal due to performance issues
            setInterval(() => {
                this.getClients();
            }, 3000);*/

            //second method
            Fire.$on('refresh',() => {
                this.getClients();
            });//listen for this event and use this callback
        }
    }
</script>
