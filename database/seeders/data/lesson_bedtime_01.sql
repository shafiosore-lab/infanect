-- Sample SQL for lesson_bedtime_01
INSERT INTO lessons (external_id, title, summary, target_age_min, target_age_max, tags, source_pdf_id, created_at, updated_at)
VALUES ('lesson_bedtime_01', 'Bedtime Routines Made Easy', 'A short, science-backed 20-minute bedtime routine for caregivers of 2–6 year olds.', 2, 6, '["bedtime","routines","sleep","toddlers"]', 'sp_123_sleep_guide.pdf', '2025-01-01 00:00:00', '2025-01-01 00:00:00');

SET @lesson_id = (SELECT id FROM lessons WHERE external_id = 'lesson_bedtime_01' LIMIT 1);

INSERT INTO media_assets (lesson_id, type, url, duration_seconds, language, generated_at)
VALUES
(@lesson_id, 'audio', '/media/lesson_bedtime_01/audio.mp3', 210, 'en', '2025-01-01 00:00:00'),
(@lesson_id, 'video', '/media/lesson_bedtime_01/video.mp4', 270, 'en', '2025-01-01 00:00:00'),
(@lesson_id, 'thumbnail', '/media/lesson_bedtime_01/thumb.png', 0, '', '2025-01-01 00:00:00');
