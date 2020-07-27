<template>
    <v-card min-height="500">
      <v-card-title class="headline">
        Новий матерiал
      </v-card-title>
      <!-- <v-btn @click="sendRequest()">Додати</v-btn> -->
      <v-card-text>
        <v-row>
          <v-col md="4" class="pb-0 pt-0">
            <v-select
              v-if="categoriesPresent"
              @blur="$v.newCategorie.$touch()"
              @change="$v.newCategorie.$touch();"
              :error-messages="newCategorieErr"
              v-model="newCategorie"
              label="Оберiть категорiю"
              :items="categories"
              item-text="name"
              item-value="id"
              outlined dense>
            </v-select>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-text class="pb-0 pt-0">
        <v-textarea
          @input="$v.materialName.$touch()"
          @blur="$v.materialName.$touch()"
          :error-messages="materialNameErr"
          v-model="materialName"
          label="Заголовок матерiалу"
          rows="2"
          outlined>
        </v-textarea>
        <v-row>
          <v-col md="4" class="pb-0">
            <!-- :disabled="!categoriesPresent" -->
            <v-file-input
              v-if="imageName === null"
              @change="previewImage($event)"
              v-model="materialImg"
              :error-messages="materialImgErr"
              :rules="rules"
              accept="image/png, image/jpeg, image/bmp"
              prepend-inner-icon="mdi-camera"
              label="Зображення до матерiалу"
              outlined show-size dense>
            </v-file-input>
          </v-col>
        </v-row>
        <div v-if="materialImgPreview !== null || imageName !== null" style="width: 365px; position: relative;">
          <v-btn @click="clearImage()" icon style="position: absolute; right: -5px; top: -35px;">
            <v-icon large color="black" v-text="'mdi-close'"></v-icon>
          </v-btn>
        </div>
        <img
          v-if="materialImgPreview !== null || imageName !== null"
          :src="materialImgPreview || imageName"
          width="365"
          style="border: 2px solid black; border-radius: 5px;"
          class="grey darken-4" />
      </v-card-text>
      <v-card-text>
        <v-card-title 
          class="headline black--text"
          style="text-decoration: underline;">
          Контент
        </v-card-title>
        <ckeditor
          :editor="editor" v-model="editorData" :config="editorConfig">
        </ckeditor>
        <v-card-text class="d-flex justify-center">
          <span>
            <v-btn 
            @click="submit()" 
            :loading="loading"
            dark 
            class="error">
              {{ $route.params.edit ? 'Зберегти' : 'Додати матерiал'}}
            </v-btn>
          </span>
        </v-card-text>
      </v-card-text>
    </v-card>
</template>

<script>
  // import '@ckeditor/ckeditor5-build-classic/build/translations/uk'
  import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter'
  import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor'
  import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials'
  import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold'
  import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic'
  import LinkPlugin from '@ckeditor/ckeditor5-link/src/link'
  import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph'
  import Heading from '@ckeditor/ckeditor5-heading/src/heading'
  import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment'
  import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat'
  import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote'

  import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption'
  import Image from '@ckeditor/ckeditor5-image/src/image'
  import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload'
  import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle'
  import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar'
  import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize'
  // import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage'

  import List from '@ckeditor/ckeditor5-list/src/list'

  import axios from 'axios'
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin],
    data: () => ({
      commonErr: ['Обов`язкове поле'],
      editor: ClassicEditor,
      editorConfig: {
        plugins: [ 
          SimpleUploadAdapter,
          EssentialsPlugin,
          BoldPlugin,
          ItalicPlugin,
          LinkPlugin,
          ParagraphPlugin,
          Heading,
          Alignment,
          Autoformat,
          BlockQuote,
          ImageCaption,
          Image,
          ImageUpload,
          ImageStyle,
          ImageToolbar,
          List,
          ImageResize,
          // LinkImage
        ],
        toolbar: {
          items: [
            'heading',
            '|',
            'alignment',
            'bold',
            'italic',
            'link',
            // 'list',
            // 'TodoList',
            'ImageUpload',
            'blockQuote',
            'undo',
            'redo',
          ]
        },
        image: {
          upload: {
            types: ['jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff']
          },
          toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],
          styles: [
                'full',
                'side',
                'alignLeft',
                'alignRight',
                'alignCenter'
            ]
        },
        language: 'uk',
        simpleUpload: {
          uploadUrl: '/admin/image/upload',
          headers: {
            'X-CSRF-TOKEN': 'CSFR-Token',
            Authorization: `Bearer this.getCsrf`
          }
        }
      },
      rules: [
        value => !value || value.size < 2000000 || 'Розмiр зображення повинен бути меньше 2 MB!',
      ],
      categories: [],
      loading: false,

      editorData: 'Контент',
      materialName: null,

      materialImg: null,
      materialImgPreview: null,
      imageName: null,
      imageToDelete: null,

      newCategorie: null
    }),
    validations: {
      newCategorie: { required },
      materialName: { required },
      materialImg: {required}
    },
    computed: {
      categoriesPresent() {
        if(!this.categories) return false
        return this.categories.length > 0
      },
      newCategorieErr() {
        if (!this.$v.newCategorie.$error) return
        return this.commonErr
      },
      materialNameErr() {
        if (!this.$v.materialName.$error) return
        return this.commonErr
      },
      materialImgErr() {
        if (!this.$v.materialImg.$error) return
        return this.commonErr
      }
    },
    methods: {
      clearImage() {
        console.log('clear image')
        this.imageName = null 
        this.materialImg = null
        this.materialImgPreview = null
        if(this.$route.params.edit === true) {
          axios
            .post('/admin/image/delete', {image: this.imageToDelete})
            .then(response => {
              console.log(response.data)
            })
            .catch(error => {
              console.log(error.response)
            })
        }
      },
      submit() {
        console.log(this.finalObj())
        !this.$v.$invalid
          ? this.sendRequest()
          : this.highlightErrors()
      },
      sendRequest() {
        this.loading = true
        let url
        if(this.$route.params.edit === true) {
          url = `/admin/useful-material/update/${this.$route.params.id}`
        } else {
          url = '/admin/useful-material/create'
        }
        axios
          .post(url, this.finalObj())
          .then(response => {
            console.log(response)
            this.loading = false
            this.$notify({
              group: 'success',
              title: 'Успiшно',
              text: ``,
            })
            setTimeout(() => {
              this.$router.push({name: 'Роздiл Кориснi матерiали', params: {reload: true}})
            }, 200);
          })
          .catch(error => {
            console.log(error.response)
            this.loading = false
            this.$notify({
              group: 'error',
              title: 'Помилка',
              text: `${error.response.status} \n ${error.response.data.message}`,
            })
          })
      },
      finalObj() {
        return {
          usefulMaterialsCategoryId: this.newCategorie,
          titleImage: this.imageName,
          title: this.materialName, 
          content: this.editorData,
          _token: this.getCsrf()
        }
      },
      previewImage(event) { 
        if(event) {
          console.log(event)
          this.imageName = event.name
          console.log('****************')
          console.log(this.imageName)
          console.log('****************')
          const reader = new FileReader()
          reader.readAsDataURL(event)
          reader.onload = (data) => {
            this.materialImgPreview = data.currentTarget.result
          }
          this.uploadTitleImage(event)
        } else {
          this.materialImgPreview = null
        }
      },
      uploadTitleImage(event) {
        let formData = new FormData()
        formData.append('upload', event)
        axios
          .post('/admin/image/upload', formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': 'CSFR-Token',
                Authorization: this.getCsrf()
              }
          })
          .then(response => {
            console.log(response)
            this.imageName = response.data.url
          })
          .catch(error => {
            console.log(error.response)
            this.$notify({
              message: 'Помилка',
              type: 'error',
            })
          })
      },
      getCsrf() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      highlightErrors() {
        this.$v.$anyError
        this.$v.$touch()
      },
      assignTokenToCkEditorConfig() {
        this.editorConfig.simpleUpload.headers.Authorization = `Bearer ${this.getCsrf()}`
      }
    },
    mounted() {
      this.assignTokenToCkEditorConfig()
      this.categories = this.$route.params.categories
    },
    created() {
      this.editorConfig
      let material = this.$route.params.material
      if(this.$route.params.edit === true) {
        this.editorData = material.content,
        this.materialName = material.title,
        this.imageName = material.title_image

        let index = material.title_image.lastIndexOf("/") + 1
        this.imageToDelete = material.title_image.substr(index)
        console.log(this.imageToDelete)

        this.materialImg = material.title_image
        this.newCategorie = this.$route.params.category[0].id
      }
    }
  }
</script>

<style lang="scss">
  .ck {
    &.ck-content {
      min-height: 200px!important;
    }
  }
</style>