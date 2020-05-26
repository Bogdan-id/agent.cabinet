<template>
    <v-card min-height="500">
      <v-card-title class="headline">
        Новий матерiал
      </v-card-title>
      <v-card-text class="pb-0">
        <v-textarea
          v-model="materialName"
          label="Заголовок матерiалу"
          rows="2"
          outlined>
        </v-textarea>
        <v-row>
          <v-col md="4" class="pb-0">
            <v-file-input
              @change="previewImage($event)"
              v-model="materialImg"
              :rules="rules"
              accept="image/png, image/jpeg, image/bmp"
              prepend-inner-icon="mdi-camera"
              label="Зображення до матерiалу" 
              outlined show-size dense>
            </v-file-input>
          </v-col>
        </v-row>
        <v-img
          v-if="materialImgPreview !== null"
          :src="materialImgPreview"
          width="365"
          style="border: 2px solid black; border-radius: 5px;"
          class="grey darken-4">
        </v-img>
      </v-card-text>
      <v-card-text class="mb-12">
        <v-card-title 
          class="headline black--text"
          style="text-decoration: underline;">
          Контент
        </v-card-title>
        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
      </v-card-text>
    </v-card>
</template>

<script>
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
  import '@ckeditor/ckeditor5-build-classic/build/translations/uk'

  export default {
    name: 'app',
    data: () => ({
      editor: ClassicEditor,
      editorData: '',
      editorConfig: {
        language: 'uk',
      },
      materialName: null,
      materialImg: null,
      materialImgPreview: null,
      rules: [
        value => !value || value.size < 2000000 || 'Розмiр зображення повинен бути меньше 2 MB!',
      ],
    }),
    methods: {
      previewImage(event) {
        if(event) {
          const reader = new FileReader()
          reader.readAsDataURL(event)
          reader.onload = (data) => {
            this.materialImgPreview = data.currentTarget.result
          }
        } else {
          this.materialImgPreview = null
        }
      },
    }
  }
</script>

<style lang="scss">
  .ck {
    &.ck-content {
      min-height: 400px!important;
    }
  }
</style>