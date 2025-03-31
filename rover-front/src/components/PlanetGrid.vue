<template>
    <div class="container">
 
      <div class="controls">
        <div class="controls-group">
          <h3>Rover Position</h3>
          <div class="rover-position">
            <label>X: {{ roverPosition.x }}</label>
            <label>Y: {{ roverPosition.y }}</label>
            <label>Direction: {{ roverPosition.direction }}</label>
          </div>
        </div>
        
        <div class="controls-group">
            <label class="show-coordinates-toggle">
          <input type="checkbox" v-model="showCoordinates">
          Show Coordinates
        </label>
        </div>
        <div class="controls-group">
          <h3>Tools</h3>
          <button @click="addObstacle" :class="{ active: addingObstacle }">
              <span v-if="addingObstacle">End adding</span>
            <span v-else>Edit Obstacle</span>
          </button>
          <small v-if="addingObstacle">Click on the grid to add/remove obstacles</small>
          <button @click="resetGrid">Reset Grid</button>
        </div>
        
        <div class="controls-group">
          <h3>Commands</h3>
          <input 
            v-model="commands" 
            placeholder="Enter commands (F, L, R)" 
            @keyup="validateCommands"
          />
          <button @click="sendCommands">Send commands</button>
          <div v-if="commandError" class="error">{{ commandError }}</div>
        </div>
        
        <div class="controls-group">
          <h3>Obstacles</h3>
          <div class="obstacles-list">
            <div v-for="(obstacle, index) in obstacles" :key="index" class="obstacle-item">
              [{{ obstacle[0] }}, {{ obstacle[1] }}]
              <button @click="removeObstacle(index)" class="remove-btn">X</button>
            </div>
            <div v-if="obstacles.length === 0">No obstacles</div>
          </div>
        </div>
      </div>
      
      <div 
        class="grid" 
        @click="handleGridClick"
      >
      <div class="loading" v-if="loading">
        <img src="@/assets/interference.gif" alt="">
      </div> 
      <div class="row" v-for="y in visibleRows" :key="y">
        <div 
          v-for="x in visibleCols" 
          :key="`${x-1}-${y-1}`"
          :class="[
            'cell', 
            isRoverHere(x-1, y-1) ? `rover rover-${roverPosition.direction}` : '',
            isObstacleHere(x-1, y-1) ? 'obstacle' : ''
          ]"
          :data-x="x-1"
          :data-y="y-1"
        >
          <span v-if="showCoordinates" class="coordinates">{{ x-1 }},{{ y-1 }}</span>
        </div>
      </div>
      </div>
      
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  
  // Grid configuration
  const visibleRows = ref(20);
  const visibleCols = ref(20);
  const viewportX = ref(0);
  const viewportY = ref(0);
  const showCoordinates = ref(false);
  
  const emit = defineEmits(['sendCommands', 'updateRoverPosition']);
  const sendCommands = () => {
    emit('sendCommands', commands.value, obstacles.value);
  };

  const { roverPosition, loading } = defineProps(['roverPosition', 'loading']);
  
  // Obstacles
  const obstacles = ref([]);
  const addingObstacle = ref(false);
  
  // Commands
  const commands = ref('');
  const commandError = ref('');
  const isCommandsValid = computed(() => {
    return commands.value.match(/^[FLR]*$/);
  });
  
  // Check if rover is at specific coordinates
  const isRoverHere = (x, y) => {
    return roverPosition.x === x && roverPosition.y === y;
  };
  
  // Check if obstacle is at specific coordinates
  const isObstacleHere = (x, y) => {
    return obstacles.value.some(o => o[0] === x && o[1] === y);
  };
  
  // Validate commands input
  const validateCommands = () => {
    if (!isCommandsValid.value && commands.value) {
      commandError.value = 'Commands can only contain F, L, and R';
    } else {
      commandError.value = '';
    }
  };
  
  // Handle clicking on the grid
  const handleGridClick = (event) => {
    if (!event.target.classList.contains('cell')) return;
    
    const x = parseInt(event.target.dataset.x);
    const y = parseInt(event.target.dataset.y);
    
    if (addingObstacle.value) {
      // Don't add obstacle where rover is
      if (isRoverHere(x, y)) return;
      
      // Don't add duplicate obstacles
      if (isObstacleHere(x, y)) {
        // Remove obstacle if clicked on existing one
        const index = obstacles.value.findIndex(o => o.x === x && o.y === y);
        if (index !== -1) {
          obstacles.value.splice(index, 1);
        }
        return;
      }
      
      obstacles.value.push([x, y]);
    } else {
      // Move rover to clicked position if not obstructed
      if (!isObstacleHere(x, y)) {
        emit('updateRoverPosition', [x, y], roverPosition.direction);
      }
    }
  };
  
  // Add obstacle mode toggle
  const addObstacle = () => {
    addingObstacle.value = !addingObstacle.value;
  };
  
  // Remove obstacle by index
  const removeObstacle = (index) => {
    obstacles.value.splice(index, 1);
  };
  
  // Reset the grid
  const resetGrid = () => {
    emit('updateRoverPosition', [0, 0], 'N');
    obstacles.value = [];
    commands.value = '';
    commandError.value = '';
    viewportX.value = 0;
    viewportY.value = 0;
  };


  </script>
  
  <style scoped>
  .container {
    display: flex;
    gap: 1rem;
    max-width: 100%;
    margin: 0 auto;

  }
  
  .controls-group {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
    gap: .5rem;
  }
  
  .controls-group h3 {
    margin: 0 0 .5rem 0;
    font-size: 1rem;
  }
  
  .grid {
    display: flex;
    flex-direction: column-reverse;
    gap: 1px;
    border: 1px solid var(--color-border);
    width: 100%;
    aspect-ratio: 1 / 1;
    position: relative;

  }
  
  .row {
    display: flex;
    gap: 1px;
    height: 5%;
  }
  
  .cell {
    width: 5%;
    background-color: var(--color-background-mute);
    position: relative;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .cell:hover {
    background-color: var(--color-background-soft);
  }
  
  .rover {
    position: relative;
    z-index: 1;
    background-image: url('@/assets/rover.png');
    background-size: 100% 100%;
    animation: flash 1s infinite;

  }
  
  .rover-N {
    transform: rotate(0deg);
  }
  
  .rover-E {
    transform: rotate(90deg);
  }
  
  .rover-S {
    transform: rotate(180deg);
  }
  
  .rover-W {
    transform: rotate(270deg);
  }
  
  .obstacle {
    background-color: #f44336;
  }
  
  .rover-position {
    display: flex;
    gap: .5rem;
  }
  
  .coordinates {
    font-size: .5rem;
    color: #999;
    pointer-events: none;
  }

  button {
    padding: 6px 12px;
    background-color: #3f51b5;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover:not(:disabled) {
    background-color: #303f9f;
  }
  
  button:disabled {
    background-color: #bdbdbd;
    cursor: not-allowed;
  }
  
  button.active {
    background-color: #ff9800;
  }
  
  .remove-btn {
    padding: 0 4px;
    font-size: 12px;
    background-color: #f44336;
  }
  
  input {
    padding: .5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .error {
    color: #f44336;
    font-size: 12px;
  }
  
  .obstacles-list {
    max-height: 100px;
    overflow-y: auto;
    font-size: 12px;
  }
  
  .obstacle-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
  }
  
  .show-coordinates-toggle {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
  }

  .loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 2;
    opacity: 0.2;

    img {
      width: 100%;
    }
  }

  @keyframes flash{

    0%,
    100% {
      background-color: #c22c2c86;
      box-shadow: 0px 0px 3.5rem 8px #c22c2c86;
    }
    50% {
      background-color: #c22c2c;
      box-shadow: 0px 0px 3.5rem 8px #c22c2c;
    }
  }
  </style>