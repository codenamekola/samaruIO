<template>
    <div class="container">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-dark text-white">
                <h3 class="card-title">Your Inventory</h3>

                <div class="card-tools">
                  <button class="btn btn-warning" data-toggle="modal" data-target="#addClientModal">Add Product <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header | npm install axios vform -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Amt In Stock</th>
                    <th>Available for Orders?</th>
                    <th>Modify</th>
                  </tr>
                  <tr>
                    <td>App Mac Book Pro</td>
                    <td>Apple laptop</td>
                    <td>$1500</td>
                    <td>12</td>
                    <td>True</td>
                    <td>
                        <a href="#"><i class="fa fa-edit icon-green"></i></a>
                        <a href="#"><i class="fa fa-trash icon-red"></i></a>
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
                    <div class="modal-header">
                        <h5 class="modal-title" id="addClientModal">Add product to inventory</h5>
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
                            :class="{ 'is-invalid': form.errors.has('location')}" placeholder="Lphone_numberocation">
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
                //create new instance of vform and specify fields
                //fields will have two way data binding, hence aiding backend validation
                form: new Form({
                    product_name: '',
                    product_description: '',
                    product_price: '',
                    stock: '',
                    available: ''
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
