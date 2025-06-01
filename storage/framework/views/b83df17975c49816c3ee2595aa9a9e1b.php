

<?php $__env->startSection('title', 'О нас'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/meropriat.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <section class="main-container1">
    <div class="main-container">
        <div class="content-section">
            <h2>Календарь событий</h2> 

            <!-- Вывод сообщений об ошибках и успехе -->
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            
            <?php
                $acceptedEvents = App\Models\Event::where('status', 'accepted')->get()->map(function($event) {
                    $event->image_url = $event->image_path ? asset('storage/' . $event->image_path) : asset('img/mer.WEBP');
                    $event->current_participants = $event->getCurrentParticipantsCount();
                    $event->has_available_spots = $event->hasAvailableSpots();
                    return $event;
                });
            ?>

            
            <div id="calendar-container">
                
                <div class="calendar-header">
                    <button id="prevMonth" class="btn"><i class="fas fa-chevron-left"></i></button>
                    <h2 id="currentMonthYear"></h2>
                    <button id="nextMonth" class="btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                <div class="calendar-weekdays"></div>
                
                <div class="calendar-days"></div>
            </div>

            
            <div id="event-popup" class="event-popup" style="display: none;">
                <div class="popup-content">
                    <button class="close-popup">&times;</button>
                    <div class="event-image">
                        <img id="popup-image" src="" alt="Event Image">
                    </div>
                    <div class="event-details">
                        <h3 id="popup-title"></h3>
                        <div class="event-info">
                            <p><i class="fas fa-calendar"></i> <span id="popup-date"></span></p>
                            <p><i class="fas fa-map-marker-alt"></i> <span id="popup-location"></span></p>
                            <p><i class="fas fa-user"></i> <span id="popup-responsible"></span></p>
                            <p><i class="fas fa-users"></i> <span id="popup-participants"></span></p>
                        </div>
                        <div class="event-description">
                            <p id="popup-description"></p>
                        </div>
                        <div class="event-actions">
                            <button id="participate-btn" class="btn-participate">Участвовать</button>
                        </div>
                    </div>
                </div>
            </div>

            
            <script>
                const events = <?php echo json_encode($acceptedEvents, 15, 512) ?>;
                window.isAuthenticated = <?php echo e(Auth::check() ? 'true' : 'false'); ?>;
                console.log('Loaded events:', events);
            </script>

        </div>
    </div>
</section>


<script src="<?php echo e(asset('js/meropriat.js')); ?>"></script>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/meropriat.blade.php ENDPATH**/ ?>