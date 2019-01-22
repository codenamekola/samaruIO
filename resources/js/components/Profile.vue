<style>
.widget-user-header{
    background-position: center;
    background-size: cover;
    height: 250px;
}


</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mt-3">
                <div class="card card-widget widget-user">

                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background-image:url('./img/bg.jpg')">
                        <h3 class="widget-user-username">{{form.name}}</h3>
                        <h5 class="widget-user-desc">{{form.bio}}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        
                    </div>
            
                </div>
            </div>
            <div class="col-md-10">

            <div class="card">
              <div class="card-header bg-dark text-white p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-12">
                          <input type="name" v-model="form.name" class="form-control" id="inputName" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-12">
                          <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="bio" class="col-sm-2 control-label">Bio</label>

                        <div class="col-sm-12">
                          <textarea class="form-control" v-model="form.bio" id="bio" placeholder="Bio"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="bio" class="col-sm-2 control-label">Photo</label>

                        <div class="col-sm-12">
                          <input type="file" name="photo" class="form-input" @change="updatePhoto">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="password" class="col-sm-10 control-label">Password (leave empty if not changing)</label>

                        <div class="col-sm-12">
                          <input type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" @click.prevent="updateProfile" class="btn btn-danger">Submit</button>
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
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    type: '',
                    password: '',
                    bio: '',
                    photo: '',
                })
            }
        },
        methods: {
            getProfilePhoto(){
              let photo = (this.form.photo.length > 100) ? this.form.photo : "img/profile/"+this.form.photo;
              return photo;
            },
            updatePhoto(e){
                //e represents the event of a file being picked or selected
                let file = e.target.files[0];//store the upload file into variable file
                //you have access to various data including file name,size.type etc.. 
                //console.log(file) can show all the properties you have access to
                let reader = new FileReader();//create a new instance of file reader
                if(file['size'] < 5000000){//check if file is less than 5mb
                    reader.onloadend = (file) => {
                    //assign base 64 string of the file to photo
                    this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);//read selected file as data 
                }else{
                    toast({
                            type: 'error',
                            title: 'file exceeds 5MB :('
                        });
                }
            },
            updateProfile(){
                this.$Progress.start();
                this.form.put('api/profile')
                    .then(()=>{
                        toast({
                            type: 'success',
                            title: 'profile updated'
                        });
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        toast({
                            type: 'error',
                            title: 'something went wrong :('
                        });
                        this.$Progress.fail();
                    });
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            //make get request and fill returned data into form 
            axios.get('api/profile').then(({data}) => {this.form.fill(data)});
        }
    }
</script>
