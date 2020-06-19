<template>
  <div>
    <v-hover 
      v-slot:default="{ hover }">
      <v-card
        :to="{name: 'Детально'}"
        :elevation="hover ? 8 : 3"
        class="d-flex ml-3 mr-3 mb-5">
        <v-row class="ml-1 mr-1">
          <v-col xs="12" sm="3">
            <div class="image-column">
              <v-img
                v-if="item.image"
                :src="item.image"
                width="365">
              </v-img>
              <!-- <v-icon v-if="!article.img">
              </v-icon> -->
            </div>
          </v-col>
          <v-col xs="12" sm="9">
            <div class="text-column pl-3 pr-3">
              <div>
                <h4 class="mt-0">{{ item.title}}</h4>
              </div>
              <div class="text">
                {{ item.text}}
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-hover>
  </div>
</template>

<script>
export default {
  props: ['item'],
  mounted() {
    console.log(this.item)
  }
}
</script>

<style scoped>
.text {
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 5; /* number of lines to show */
   -webkit-box-orient: vertical;
}
</style>