-- ============================================================
-- RACE CONDITION PREVENTION - DATABASE CONSTRAINTS
-- Execute this SQL to add unique constraints that prevent race conditions
-- at the database level.
-- ============================================================

-- 1. ATTENDANCE TABLE
-- Prevent duplicate attendance records for same student/date/term/session
ALTER TABLE `attendance` 
ADD UNIQUE INDEX `idx_attendance_unique` (`student_id`, `date`, `term_id`, `session_id`);

-- 2. MASTERSHEET TABLE
-- Prevent duplicate result entries for same student/subject/term/session
ALTER TABLE `mastersheet`
ADD UNIQUE INDEX `idx_mastersheet_unique` (`id`, `subject`, `term`, `csession`);

-- 3. STUDENT_FEES TABLE
-- Prevent duplicate fee assignments for same student/structure/term/session
ALTER TABLE `student_fees`
ADD UNIQUE INDEX `idx_student_fees_unique` (`student_id`, `fee_structure_id`, `term`, `session`);

-- 4. STUDENT_FEE_ITEMS TABLE
-- Prevent duplicate fee item entries for same student_fee/fee_item
ALTER TABLE `student_fee_items`
ADD UNIQUE INDEX `idx_student_fee_items_unique` (`student_fee_id`, `fee_item_id`);

-- 5. PAYMENTS TABLE
-- Prevent duplicate receipt numbers
ALTER TABLE `payments`
ADD UNIQUE INDEX `idx_receipt_unique` (`receipt_number`);

-- 6. PAYMENT_ALLOCATIONS TABLE
-- Prevent duplicate allocations for same payment/fee_item/term/session
ALTER TABLE `payment_allocations`
ADD UNIQUE INDEX `idx_payment_allocations_unique` (`payment_id`, `student_fee_item_id`, `term`, `session`);

-- 7. CBT_ANSWERS TABLE
-- Prevent duplicate answers for same session/question
ALTER TABLE `cbt_answers`
ADD UNIQUE INDEX `idx_cbt_answers_unique` (`session_id`, `question_id`);

-- 8. CBT_SESSIONS TABLE
-- Ensure one active session per student/exam
ALTER TABLE `cbt_sessions`
ADD UNIQUE INDEX `idx_cbt_sessions_student_exam` (`student_id`, `exam_id`);

-- 9. TRANSACTIONS TABLE
-- Prevent duplicate transaction entries (if not already exists)
ALTER TABLE `transactions`
ADD UNIQUE INDEX `idx_transactions_unique` (`student_id`, `type`, `reference`, `term`, `session`);

-- ============================================================
-- ADDITIONAL INDEXES FOR PERFORMANCE
-- ============================================================

-- Index for FOR UPDATE queries on students
ALTER TABLE `students`
ADD INDEX `idx_students_status` (`status`);

-- Index for fee item lookups
ALTER TABLE `fee_structure_items`
ADD INDEX `idx_fee_structure_items_structure` (`fee_structure_id`);

-- Index for student fee items lookups
ALTER TABLE `student_fee_items`
ADD INDEX `idx_student_fee_items_paid` (`student_fee_id`, `paid_amount`);

-- ============================================================
-- IF CONSTRAINTS ALREADY EXIST, USE THESE ALTERATIVE COMMANDS:
-- ============================================================
-- First check if indexes exist, then add if missing:
-- SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS 
-- WHERE TABLE_SCHEMA = 'your_database_name' AND TABLE_NAME = 'attendance';

-- To drop existing index before adding new one:
-- ALTER TABLE `attendance` DROP INDEX `idx_attendance_unique`;
-- Then run the ADD UNIQUE INDEX command above.