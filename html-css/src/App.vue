<script setup>
const nama = "Anton";
</script>

<template>
  <dir>{{ nama }}</dir>
  <div class="background">
    <nav class="container">
      <div class="logo">Logo</div>
      <ul>
        <li>Home</li>
        <li>About</li>
        <li>Contact us</li>
      </ul>
    </nav>
  </div>
</template>

<style scoped>
h1 {
  font-size: 20px;
}
.logo {
  border-left: 5px solid teal;
  padding-left: 3px;
}
ul {
  display: flex;
  flex-direction: row;
  list-style: none;
  gap: 20px;
}
nav {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  color: white;
}
.container {
  padding: 0 40px;
}
.background {
  background-image: url("/assets/computer.jpg");
  height: 50vh;
  padding-top: 20px;
  background-size: cover;
  background-position: bottom;
}
</style>
