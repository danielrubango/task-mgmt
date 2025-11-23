import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit when project is selected
    const projectSelect = document.getElementById('projects');
    if (projectSelect) {
        projectSelect.addEventListener('change', function() {
            if (this.value) {
                window.location.href = `/projects/${this.value}`;
            }
        });
    }

    // Allow ESC key to cancel editing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllEditForms();
        }
    });

    // Initialize drag and drop
    initDragAndDrop();
});

// Close all edit forms (tasks and project)
window.closeAllEditForms = function() {
    // Close task edit form opened
    document.querySelectorAll('[class*="task-edit-"]').forEach(form => {
        if (!form.classList.contains('hidden')) {
            const taskId = form.className.match(/task-edit-(\d+)/)[1];
            const displayElement = document.querySelector(`.task-display-${taskId}`);
            if (displayElement) {
                displayElement.classList.remove('hidden');
                form.classList.add('hidden');
            }
        }
    });

    // Close project edit form opened
    document.querySelectorAll('[class*="project-edit-"]').forEach(form => {
        if (!form.classList.contains('hidden')) {
            const projectId = form.className.match(/project-edit-(\d+)/)[1];
            const displayElement = document.querySelector(`.project-display-${projectId}`);
            if (displayElement) {
                displayElement.classList.remove('hidden');
                form.classList.add('hidden');
            }
        }
    });
};

// Toggle edit mode for project
window.toggleEditProject = function(projectId) {
    const displayElement = document.querySelector(`.project-display-${projectId}`);
    const editElement = document.querySelector(`.project-edit-${projectId}`);
    
    if (!displayElement || !editElement) return;
    
    // Check if opening or closing the project
    const isOpening = editElement.classList.contains('hidden');
    
    // Close all edit forms opened first
    window.closeAllEditForms();
    
    // If opening, show the project edit form
    if (isOpening) {
        displayElement.classList.add('hidden');
        editElement.classList.remove('hidden');
        
        // Focus on input when showing edit form
        const input = editElement.querySelector('input[name="title"]');
        input?.focus();
        input?.select();
    }
};

// Toggle edit mode for task
window.toggleEditTask = function(taskId) {
    const displayElement = document.querySelector(`.task-display-${taskId}`);
    const editElement = document.querySelector(`.task-edit-${taskId}`);
    
    if (!displayElement || !editElement) return;
    
    // Check if opening or closing the task
    const isOpening = editElement.classList.contains('hidden');
    
    // Close all edit forms opened first
    window.closeAllEditForms();
    
    // If opening, show the task edit form
    if (isOpening) {
        displayElement.classList.add('hidden');
        editElement.classList.remove('hidden');
        
        // Focus on input when showing edit form
        const input = editElement.querySelector('input[name="title"]');
        input?.focus();
        input?.select();
    }
};

// Drag and Drop
function initDragAndDrop() {
    const taskList = document.getElementById('sortable-tasks');
    if (!taskList) return;

    let draggedItem = null;
    let placeholder = null;

    // Create placeholder using only Tailwind classes
    function createPlaceholder() {
        const div = document.createElement('div');
        div.className = 'placeholder border-2 border-dashed border-blue-400 bg-blue-50 rounded-lg h-[60px] my-0';
        return div;
    }

    // Get all task items
    const tasks = taskList.querySelectorAll('.task-item');

    tasks.forEach(task => {
        const handle = task.querySelector('.drag-handle');
        
        // Enable dragging only from handle
        handle.addEventListener('mousedown', () => {
            task.draggable = true;
        });

        // Drag start
        task.addEventListener('dragstart', (e) => {
            draggedItem = task;
            
            // Add opacity using Tailwind
            task.classList.add('opacity-40');
            
            // Create placeholder and insert it at the dragged item's position
            placeholder = createPlaceholder();
            task.parentNode.insertBefore(placeholder, task.nextSibling);
            
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', task.innerHTML);
        });

        // Drag end
        task.addEventListener('dragend', () => {
            // Remove opacity using Tailwind
            task.classList.remove('opacity-40');
            task.draggable = false;
            
            // Remove placeholder
            if (placeholder && placeholder.parentNode) {
                placeholder.remove();
                placeholder = null;
            }
            
            draggedItem = null;
            
            // Save order to database
            saveTaskOrder();
        });

        // Drag over
        task.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            
            if (!draggedItem || task === draggedItem) return;
            if (!placeholder) return;
            
            // Get mouse position relative to task
            const rect = task.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            
            // Insert placeholder above or below based on mouse position
            if (e.clientY < midpoint) {
                // Insert before this task
                if (task.previousElementSibling !== placeholder) {
                    task.parentNode.insertBefore(placeholder, task);
                }
            } else {
                // Insert after this task
                if (task.nextElementSibling !== placeholder) {
                    task.parentNode.insertBefore(placeholder, task.nextSibling);
                }
            }
        });

        // Drop
        task.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // Allow drop on container
    taskList.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    // Drop on container (handles the actual move)
    taskList.addEventListener('drop', (e) => {
        e.preventDefault();
        
        if (placeholder && placeholder.parentNode && draggedItem) {
            // Move dragged item to placeholder position
            placeholder.parentNode.insertBefore(draggedItem, placeholder);
        }
    });

    // Save task order to database (background operation)
    function saveTaskOrder() {
        // Get current order from DOM
        const tasks = Array.from(taskList.querySelectorAll('.task-item'));
        const taskIds = tasks.map(task => parseInt(task.dataset.taskId));
        const projectId = taskList.dataset.projectId;

        console.log('Saving order:', taskIds);

        // Send to server in background
        fetch(`/projects/${projectId}/tasks/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ task_ids: taskIds })
        })
        .then(response => response.json())
        .then((response) => {
            console.log(response);
            showNotification(response.message, 'success');
        })
        .catch((error) => {
            showNotification('Failed to save order', 'error');
            console.log(error);
        });
    }

    // Show notification (Tailwind only)
    function showNotification(message, type) {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity duration-500`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('opacity-0');
            setTimeout(() => notification.remove(), 500);
        }, 2000);
    }
}