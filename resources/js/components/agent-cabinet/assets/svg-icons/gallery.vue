<template>
<svg :width="width" :height="height" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
  <g data-name="Layer 2">
    <path d="M28.31,26.75H3.69A2.42,2.42,0,0,1,1.4,25.14a2.32,2.32,0,0,1-.15-.83V7.69A2.44,2.44,0,0,1,3.69,5.25H28.31a2.44,2.44,0,0,1,2.44,2.44V24.31a2.36,2.36,0,0,1-.16.85A2.42,2.42,0,0,1,28.31,26.75ZM3.69,6.75a.94.94,0,0,0-.94.94V24.31a.87.87,0,0,0,.06.31.93.93,0,0,0,.88.63H28.31a.92.92,0,0,0,.87-.61.91.91,0,0,0,.07-.33V7.69a.94.94,0,0,0-.94-.94Z"/>
    <path d="M22.81,26.75H3.69A2.44,2.44,0,0,1,1.93,26a1.43,1.43,0,0,1,0-2l7.71-7.71a1.9,1.9,0,0,1,2.69,0ZM11,17.23a.4.4,0,0,0-.28.12L3,25.05a1.48,1.48,0,0,0,.68.2h15.5l-7.91-7.91A.4.4,0,0,0,11,17.23Z"/>
    <path d="M28.31 26.75H20.69L14.94 21l4.72-4.72a1.9 1.9 0 0 1 2.69 0l7.6 7.6a1.61 1.61 0 0 1 .47 1.18 1.48 1.48 0 0 1-.5 1.07A2.43 2.43 0 0 1 28.31 26.75zm-7-1.5h7a.93.93 0 0 0 .62-.23l.49-.6-.53.53-7.6-7.6a.4.4 0 0 0-.56 0L17.06 21zM16 14.17a2.76 2.76 0 1 1 .26 0zm0-4a1.25 1.25 0 1 0 1 2h0a1.25 1.25 0 0 0-1-2z"/>
    <path d="M21,26H3.69a1.67,1.67,0,0,1-1.22-.52.69.69,0,0,1,0-1l7.71-7.71a1.15,1.15,0,0,1,1.62,0Z"/>
    <path d="M29.42,24.42a.81.81,0,0,1,0,1.16,1.67,1.67,0,0,1-1.11.42H21l-5-5,4.19-4.19a1.15,1.15,0,0,1,1.62,0Z"/>
    <circle cx="16" cy="11.42" r="2" transform="rotate(-50.32 16 11.413)"/>
  </g>
</svg>
</template>

<script>
export default {
  props: ['width', 'height']
}
</script>

<style scoped>
path {
  fill: #808080;
}
circle {
  fill: #808080;
}
</style>