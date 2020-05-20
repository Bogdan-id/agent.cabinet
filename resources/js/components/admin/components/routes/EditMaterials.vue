<template>
    <v-card min-height="500">
      <v-card-title class="headline">
        Новий матерiал
      </v-card-title>
      <v-card-text>
        <v-text-field
          v-model="materialName"
          label="Назва"
          dense>
        </v-text-field>
        <v-row>
          <v-col md="4">
            <v-file-input
              :rules="rules"
              accept="image/png, image/jpeg, image/bmp"
              prepend-icon="mdi-camera"
              label="Зображення" 
              outlined show-size>
            </v-file-input>
          </v-col>
        </v-row>
      </v-card-text>
      <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
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
      materialImgPreview: null,
      rules: [
        value => !value || value.size < 2000000 || 'Avatar size should be less than 2 MB!',
      ],
    })
  }
</script>

<style lang="scss">
  .ck {
    &.ck-content {
      min-height: 400px!important;
    }
  }
</style>