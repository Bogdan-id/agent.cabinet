<template>
    <div class="row">
      <bread-scrumb />  
      <div class="col-md-12 useful-materials">
        <useful-material-categories v-show="$route.name === 'Кориснi матерiали'">
        </useful-material-categories>
        <router-view></router-view>
      </div>
    </div>
</template>
<script>
import BreadScrumb from '../components/breadScrumb'
import UsefulMaterialCategories from './UsefulMaterialCategories'

export default {
  components: {
    BreadScrumb,
    UsefulMaterialCategories
  },
}
</script>
