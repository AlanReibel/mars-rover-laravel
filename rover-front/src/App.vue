<script setup>
  import { ref } from 'vue';
  import PlanetGrid from './components/PlanetGrid.vue';

  // Rover state
  const roverPosition = ref({
    x: 0,
    y: 0,
    direction: 'N'
  });

  const updateRoverPosition = (newPosition, direction) => {
    // Update rover position in the UI
    roverPosition.value = { x: newPosition[0], y: newPosition[1], direction };
    console.log('updateRoverPosition',{newPosition, direction});
  };

  const loading = ref(false);
  const sendCommands = (commands, obstacles) => {
    loading.value = true;
    console.log('commands',commands);
    const myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    const raw = JSON.stringify({
      "x": roverPosition.value.x,
      "y": roverPosition.value.y,
      "direction": roverPosition.value.direction,
      "commands": commands,
      "obstacles": obstacles
    });

    const requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow"
    };
    const url = "http://127.0.0.1:8000/api/rover/move";



    fetch(url, requestOptions)
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        return response.json();
      })
      .then((result) => {
        console.log('result',result);
        const { position: newPosition, direction, status} = result;
        alert(status);
        updateRoverPosition(newPosition, direction);
      })
      .catch((error) => console.error(error))
      .finally(() => {
        loading.value = false;
      });
}


</script>

<template>
  <PlanetGrid 
    @sendCommands="sendCommands" 
    @updateRoverPosition="updateRoverPosition" 
    :roverPosition="roverPosition"
    :loading="loading"
  >
    </PlanetGrid>

</template>

<style scoped>

</style>
