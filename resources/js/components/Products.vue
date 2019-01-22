<template>
    <div class="container">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-dark text-white">
                <h3 class="card-title">Your current inventory</h3>

                <div class="card-tools">
                  <button class="btn btn-warning" @click="newProductModal">Add Product <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header | npm install axios vform -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Available for orders</th>
                    <th>Created</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="product in products" :key="product.id">
                    <td>{{product.product_name | capitalName}}</td>
                    <td>{{product.product_description}}</td>
                    <td>{{product.product_price}}</td>
                    <td>{{product.stock}}</td>
                    <td>{{product.available}}</td>
                    <td><code>{{product.created_at | customDate}}</code></td>
                    <td>
                        <a href="#" @click="editProductModal(product)"><i class="fa fa-edit icon-green"></i></a>
                        <a href="#" @click="deleteProduct(product.id)"><i class="fa fa-trash icon-red"></i></a>
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
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateProduct() : createProduct()">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title" id="addProductModal">Add product to CRM</h5>
                        <h5 v-show="editMode" class="modal-title" id="addProductModal">Update Product Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.product_name" type="text" name="product_name" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('product_name')}" placeholder="Product Name">
                            <has-error :form="form" field="product_name"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.product_description" type="text" name="product_description" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('product_description')}" placeholder="Product Description">
                            <has-error :form="form" field="product_description"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.product_price" type="text" name="product_price" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('product_price')}" placeholder="Product Price">
                            <has-error :form="form" field="product_price"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.stock" type="text" name="stock" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('stock')}" placeholder="Stock">
                            <has-error :form="form" field="stock"></has-error>
                        </div>

                        <div class="form-group">
                            <select v-model="form.available" name="available" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('available')}">
                                <option value="" selected disabled hidden>Availability</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <has-error :form="form" field="available"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-show="!editMode" class="btn btn-primary" type="submit">Add Product</button>
                        <button v-show="editMode" class="btn btn-primary" type="submit">Update Product</button>
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
                products: {},//object to hold products data that comes back from database
                //create new instance of vform and specify fields
                //fields will have two way data binding, hence aiding backend validation
                form: new Form({
                    id: '',
                    product_name: '',
                    product_description: '',
                    product_price: '',
                    stock: '',
                    available: ''
                })
            }
        },
        methods: {
            getProducts(){
                //get the products from the controller using axios
                //use a promise to assign the returned data to the products object
                //use data.data cos response would come wrapped inside a data object
                axios.get('api/product').then(({data}) => {this.products = data.data});
            },
            createProduct(){
                this.$Progress.start();//trigger vue progress bar to start
                //make post request to the necessary endpoint
                this.form.post('api/product')
                    .then(() => {
                        //success case
                        Fire.$emit('refresh');//define custom event that is fired everytime a product is created
                        $('#addProductModal').modal('hide');//close the modal after client creation
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
            updateProduct(){
                this.$Progress.start();
                this.form.put('api/product/'+this.form.id)
                    .then(()=>{
                        Fire.$emit('refresh');
                        $('#addProductModal').modal('hide');
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
            deleteProduct(id){
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
                        this.form.delete('api/product/'+id)
                        .then(()=>{
                                swal('Deleted!','Product has been deleted.','success');
                                Fire.$emit('refresh');
                        })
                        .catch(()=>{
                            swal('Failed!','Unable to delete product.','warning');
                        });
                    }
                });
            },
            newProductModal(){
                this.editMode = false;//toggle editmode to false
                this.form.reset();//clear all previous client data
                $('#addProductModal').modal('show');
            },
            editProductModal(product){
                this.editMode = true;//toggle editmode to true
                this.form.reset();
                $('#addProductModal').modal('show');
                this.form.fill(product);//fill modal fields with product data
            }
        },
        created() {
            this.getProducts();//will get all clients once component is created
            //query database every 3 seconds for new data
            /*first method - not ideal due to performance issues
            setInterval(() => {
                this.getClients();
            }, 3000);*/

            //second method
            Fire.$on('refresh',() => {
                this.getProducts();
            });//listen for this event and use this callback
        }
    }
</script>
